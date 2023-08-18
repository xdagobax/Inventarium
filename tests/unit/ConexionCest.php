<?php

use DgbAuroCore\vendor\Inventarium\tests\mockups\conexion;
use TestCore\UnitTester;



class ConexionCest
{
    function __construct()
    {
        require_once __DIR__ . '../../../../dgbdebugger/debug.php';
    }


    public function testCTEs(UnitTester $I)
    {
        require_once __DIR__ . '/../mockups/conexion.php';

        $cnx = new conexion();
        $I->assertStringContainsString('soporta', $cnx->testCTEs(), 'se espera la palabra "soporta"');
    }

}
