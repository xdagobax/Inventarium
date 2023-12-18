<?php

use DgbAuroCore\vendor\Inventarium\Facade;
use TestCore\UnitTester;

class FacadeCest
{
    function __construct()
    {
        require_once __DIR__ . '/../../../../../lib/DgbDebugger/debug.php';

    }

    // function delegateEvent(UnitTester $I)
    // {
    //     // require_once __DIR__ . '/../../loader.php';
    //     // require_once __DIR__ . '/../../aliases.php';
    //     // require __DIR__ . '/../../../../../aurora/aliases.php';

    //     // $receptor = Facade::call('MkClass');
    //     // $emisor = Facade::call('MkClass');

    //     // Facade::delegateEvent($receptor, 'onTestEvent');
        
    //     // $emisor->triggerDelegatedEvents();

    //     // $I->assertTrue($receptor->onTestEvent , 'El evento se lanzo con exito ');

    // }

    public function facadeClasesYParametros(UnitTester $I)
    {

        require_once __DIR__ . '/../../../../../aurora/loader.php';
        require_once __DIR__ . '/../../loader.php';
        require_once __DIR__ . '/../../aliases.php';
        require __DIR__ . '/../../../../../aurora/aliases.php';
        require_once __DIR__ . '/../mockups/WithConstructParams.php';

        //Al enviar parametros necesarios debe instanciar
        $paramA = 'parametro a';
        $paramB = 'parametro b';
        $WithConstructParams = Facade::call('WithConstructParams', [$paramA]);
        $I->assertStringContainsString($paramA, $WithConstructParams->a, 'con UN parametro de DOS');
        $WithConstructParams = Facade::call('WithConstructParams', [$paramA,$paramB]);
        $I->assertStringContainsString($paramA.$paramB, $WithConstructParams->a.$WithConstructParams->b, 'con DOS parametro de DOS');
        $I->assertInstanceOf('DgbAuroCore\vendor\Inventarium\tests\mockups\WithConstructParams', $WithConstructParams, 'Facade instancia con parametros');

        //instanciar clase sin parametros cuando son requeridos
        //debe fallar

        try {
            Facade::call('WithConstructParams');
        } catch (Throwable $exception) {
            $I->expectThrowable(Exception::class, function () use ($exception, $I) {
                throw $exception;
            }, 'Excepción falta de argumentos en facade::call');
        }
        
        


    }

    
    public function FacadeAliasAdding(UnitTester $I)
    {
        require_once __DIR__ . '/../../loader.php';
        require_once __DIR__ . '/../../aliases.php';
        require_once __DIR__ . '/../../../../../aurora/loader.php';
        require __DIR__ . '/../../../../../aurora/aliases.php';

        
        $I->assertContains('DgbAuroCore\vendor\Inventarium\Cnx', Facade::$aliases, 'Facade: El alias para "DgbAuroCore\vendor\Inventarium\Cnx" debe estar en el array de aliases');
        
        
        $I->assertTrue(!is_null(Facade::call('Env')::env('ROOT')), 'Ejecutando Facade call() con clase estatic "Env" y ejecutando su metodo "env" ');

    }

    public function FacadeMsgClasesSinAlias(UnitTester $I)
    {
        // require_once __DIR__ . '/../../../../../aurora/loader.php';
        require_once __DIR__ . '/../../loader.php';
        // require __DIR__ . '/../../../../../aurora/aliases.php';


           
        try {
            Facade::call('foo');
        } catch (Throwable $exception) {
            // Verificar la excepción utilizando expectThrowable con mensaje personalizado
            $I->expectThrowable(Exception::class, function () use ($exception, $I) {
                throw $exception;
            }, 'Se espera excepción instancia de clase sin alias');

            $I->comment($exception->getMessage());

        }

    }

    
}
