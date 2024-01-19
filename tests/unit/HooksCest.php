<?php
// TODO y el namespace?
use DgbAuroCore\vendor\Inventarium\Facade;
use TestCore\UnitTester;

class HooksCest
{
    function __construct()
    {
        //TODO por que requiero el debuuger a parte ? ¿Nohayuna interfaz de dependencias?
        require_once __DIR__ . '/../../../DgbDebugger/debug.php';

    }

    //TODO esta funcion debo verla correr para entenderla, refactorizar y comentar
    function testHooks(UnitTester $I)
    {
        //TODO los requires deberían ir en el constructor, el rendimiento no es un requisito en las pruebas, almenos no a ese nivel
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

    //TODO no hay assertions?
    function testActionAsEvent(UnitTester $I)
    {


        //TODO los requires no van aqui
        require_once __DIR__ . '/../../loader.php';
        require_once __DIR__ . '/../../aliases.php';



        $fn = function ($dato) {
            return "$dato  funcion colgada al hook  desde \n";
        };

        Facade::addAction('triggerActionAsEvent', $fn, 3);

        Facade::call('HookSubject')->triggerActionAsEvent('Dato enviado a ');
    }
}
