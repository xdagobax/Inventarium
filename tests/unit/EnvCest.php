<?php
namespace TestCore;

use DgbAuroCore\lib\Inventarium\Facade;
use TestCore\UnitTester;


//TODO esto debe ir en los test de app que es donde esta Env?
class EnvCest
{
    function __construct()
    {
        require_once __DIR__ . '/../../../DgbDebugger/debug.php';

    }


    public function envVarHaveNullDefaultValue(UnitTester $I)
    {
        require_once __DIR__ . '/../../loader.php';
        require __DIR__ . '/../../../../aurora/aliases.php';
        require_once __DIR__ . '/../../../../aurora/loader.php';


        try {
            Facade::call('Env')::env('foo');
        } catch (\Throwable $exception) {
            $I->expectThrowable(\Exception::class, function () use ($exception, $I) {
                throw $exception;
            }, 'La env foo no existe');
            $I->comment($exception->getMessage());

        }
    }


    public function envFuncionEsAccesible(UnitTester $I)
    {
        require_once __DIR__ . '/../../loader.php';
        require __DIR__ . '/../../../../aurora/aliases.php';
        require_once __DIR__ . '/../../../../aurora/loader.php';

        

        $I->assertTrue(!is_null(Facade::call('Env')::env('ROOT')), 'env("ROOT") ¿Es accesible y tiene un valor? ');
        $I->comment('env("ROOT") si es accesible y tiene un valor: ' . strval(Facade::call('Env')::env('ROOT')));
    }

}
