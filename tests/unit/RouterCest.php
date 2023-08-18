<?php

use DgbAuroCore\vendor\Inventarium\Facade;
use DgbAuroCore\vendor\Inventarium\Router;
use TestCore\UnitTester;



class RouterCest
{
    function __construct()
    {
        require_once __DIR__ . '../../../../dgbdebugger/debug.php';
    }

   
    function routerWithObjectParams(UnitTester $I)
    {

        //TODO es facil olvidar que pruebas como esta estan implementadas y son importantes para escenarios ficticios.
        require_once __DIR__ . '/../../loader.php';
        require_once __DIR__ . '/../../aliases.php';
        require_once __DIR__ . '/../../../../../aurora/loader.php';
        require __DIR__ . '/../../../../../aurora/aliases.php';
        require_once __DIR__ . '/../mockups/WithConstructParams.php';

        Facade::call('Env')::init();


        $_SERVER['REQUEST_URI'] = '/aurora/public/testget?dgb=1';
        $router = new Router($_SERVER['REQUEST_URI']);

        $router->add('/testget', ['WithConstructParams', 'testget'], ['parametro de prueba']);
        ob_start(); // Iniciar el buffer de salida

        $router->run();
        // Capturar el resultado de echo en una variable
        $mensaje = ob_get_clean();

        $I->assertStringContainsString('testget', $mensaje, 'Con query params se espera testget Done!');

    }

    
}
