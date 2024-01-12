<?php

use DgbAuroCore\lib\Inventarium\Facade;
use TestCore\UnitTester;

class HooksCest
{
    function __construct()
    {
        require_once __DIR__ . '/../../../DgbDebugger/debug.php';

    }

    function testHooks(UnitTester $I)
    {
        require_once __DIR__ . '/../../loader.php';
        require_once __DIR__ . '/../../aliases.php';

        $subjectData = Facade::call('HookSubject')->getData('Soy el dato original');
        $I->assertStringContainsString('Soy el dato original', $subjectData,  'dato: ' . $subjectData);


        $ex1 = function ($dato) {
            return $dato . ' prioridad 1';
        };

        $ex2 = function ($dato) {
            return $dato . ' prioridad 2';
        };

        Facade::addFilter('replace_data', $ex2, 3);
        Facade::addFilter('replace_data', $ex1, 4);

        $subjectData = Facade::call('HookSubject')->getData('Soy el dato original');
        $I->assertStringContainsString('prioridad 1 prioridad 2', $subjectData,  ' ASC');


        $ex1 = function ($dato) {
            return $dato . ' prioridad 1';
        };

        $ex2 = function ($dato) {
            return $dato . ' prioridad 2';
        };

        Facade::addFilter('replace_data', $ex2, 3);
        Facade::addFilter('replace_data', $ex1, 2);

        $subjectData = Facade::call('HookSubject')->getData('Soy el dato original');
        $I->assertStringContainsString('prioridad 1 prioridad 2 prioridad 2 prioridad 1', $subjectData, 'Sin remover el filtro');

        Facade::removeFilter('replace_data');

        $subjectData = Facade::call('HookSubject')->getData('Soy el dato original');
        $I->assertStringNotContainsString('prioridad 1 prioridad 2 prioridad 2 prioridad 1', $subjectData, 'Removiendo filtro');


        $ex1 = function ($dato) {
            return $dato . ' prioridad 1';
        };

        $ex2 = function ($dato) {
            return $dato . ' prioridad 2';
        };

        Facade::addFilter('replace_data', $ex2, 3);
        Facade::addFilter('replace_data', $ex1, 2);


        $subjectData = Facade::call('HookSubject')->getData('Soy el dato original');
        $I->assertStringContainsString('prioridad 2 prioridad 1', $subjectData,  ' DESC');
    }

    /**
     * @group grupo_problem
     */
    function testActionAsEvent(UnitTester $I)
    {


        require_once __DIR__ . '/../../loader.php';
        require_once __DIR__ . '/../../aliases.php';



        $fn = function ($dato) {
            return "$dato  funcion colgada al hook  desde \n";
        };

        $addaction = Facade::addAction('triggerActionAsEvent', $fn, 3);

        // var_dump($addaction);
        $hookExecutedResult = Facade::call('HookSubject')->triggerActionAsEvent('Dato enviado a ');
    }
}
