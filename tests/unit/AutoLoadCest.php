<?php
//TODO y el namespace?
use TestCore\UnitTester;


class AutoLoadCest
{
    function __construct()
    {
        require_once __DIR__ . '/../../../DgbDebugger/debug.php';
    }



    
    public function testAutoloadClasses(UnitTester $I)
    {
        require_once __DIR__ . '/../../../../aurora/loader.php';
        require_once __DIR__ . '/../../loader.php';


        // Verificar que las clases se hayan cargado correctamente
        $I->assertTrue(class_exists('DgbAurora\Env'), 'Autoload carga Env');
        $I->assertTrue(class_exists('DgbAuroCore\vendor\Inventarium\Controller'), 'Autoload carga Controller');
    }
}
