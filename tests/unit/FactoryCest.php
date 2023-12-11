<?php

use DgbAuroCore\vendor\Inventarium\Factory;
use TestCore\UnitTester;

class FactoryCest
{
    function __construct()
    {
        require_once __DIR__ . '../../../../dgbdebugger/debug.php';
    }

    // public function factoryBuild(UnitTester $I)
    // {

    //     // require_once __DIR__ . '/../../loader.php';

    //     // $factory = new Factory(['Cnx' => 'DgbAuroCore\vendor\Inventarium\Cnx']);

    //     // $instance  = $factory->build('Cnx', []);

    //     // $I->assertInstanceOf('DgbAuroCore\vendor\Inventarium\Cnx', $instance, 'Factory->build creo la clase');
    // }


    public function factoryGetRealName(UnitTester $I)
    {

        require_once __DIR__ . '/../../loader.php';

        $factory = new Factory(['Cnx' => 'DgbAuroCore\vendor\Inventarium\Cnx']);

        // Acceder a la función privada utilizando reflexión
        $reflection = new \ReflectionClass($factory);
        $method = $reflection->getMethod('getRealName');
        $method->setAccessible(true);

        $name = 'Cnx';
        $result = $method->invoke($factory, $name);
        $expectedResult = 'DgbAuroCore\vendor\Inventarium\Cnx';
        // Realizar aserciones en el resultado
        $I->assertEquals($expectedResult, $result, 'Factory->getRealName obtiene el nombre real buscado por alias');
    }

}
