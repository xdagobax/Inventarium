<?php

use DgbAuroCore\vendor\Inventarium\Factory;
use TestCore\UnitTester;

class FactoryCest
{
    function __construct()
    {
        require_once __DIR__ . '/../../../../../lib/DgbDebugger/debug.php';
        require_once __DIR__ . '/../../loader.php';
        require_once __DIR__ . '/../../aliases.php';

    }


    public function factoryInyectObjects(UnitTester $I){

        $factory = new Factory(['TestFactory' => 'DgbAuroCore\vendor\Inventarium\tests\mockups\TestFactory']);

        $factory->addAlias('Controller', 'DgbAuroCore\vendor\Inventarium\Controller');
        $factory->addAlias('Model', 'DgbAuroCore\vendor\Inventarium\Model');
        $factory->addAlias('DgbAuroCore\vendor\Inventarium\interfaces\SessionManagerInterface', 'DgbAuroCore\vendor\Inventarium\Sentinela');

        $instance  = $factory->build('TestFactory');

        $field = $instance->model->addFields(['campo de prueba'])[0];
        $I->assertTrue($field =='campo de prueba', 'Esperado: ' . $field);

        $I->assertInstanceOf('DgbAuroCore\vendor\Inventarium\Model', $instance->model, 'Instancia de Model inyectada');
        $I->assertInstanceOf('DgbAuroCore\vendor\Inventarium\Controller', $instance->controller, 'Instancia de Controller inyectada');
        
        $I->assertInstanceOf('DgbAuroCore\vendor\Inventarium\tests\mockups\TestFactory', $instance, 'Factory->build creo la clase');


    }

    public function factoryBuild(UnitTester $I)
    {


        $factory = new Factory(['Cnx' => 'DgbAuroCore\vendor\Inventarium\Cnx']);

        $instance  = $factory->build('Cnx', []);

        $I->assertInstanceOf('DgbAuroCore\vendor\Inventarium\Cnx', $instance, 'alias convencional');

        //Proporcionando el nombre real de la clase tal como lo hace factory en las llamadas recursivas cuando encuentra parametros de tipo clase
        $instance  = $factory->build('DgbAuroCore\vendor\Inventarium\Cnx', []);

        $I->assertInstanceOf('DgbAuroCore\vendor\Inventarium\Cnx', $instance, 'Recursivo usa nombre de la clase');
    }


    public function getRealName(UnitTester $I)
    {


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
