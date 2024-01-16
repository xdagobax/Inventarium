<?php

use DgbAuroCore\vendor\Inventarium\tests\mockups\MkModelSun;
use DgbAuroCore\vendor\Inventarium\Facade;
use TestCore\UnitTester;

class ModelCest
{
    function __construct()
    {
        require_once __DIR__ . '/../../../DgbDebugger/debug.php';
    }

    
    public function redBean(UnitTester $I)
    {
        
        require_once __DIR__ . '/../../loader.php';
        require_once __DIR__ . '/../../aliases.php';

        //Tengo que usar estos requires porque el constructor de los modelos (Model) usa Env
        require_once __DIR__ . '/../../../../aurora/loader.php';
        require __DIR__ . '/../../../../aurora/aliases.php';


        $modelsun = new MkModelSun();
    }

    //TODO este test prueba la app y un modelo (Domains) del cliente ... No deberiamos usar un mockup o probar directo la clase modelo del core ?
    public function retrieveRegisterByValue(UnitTester $I){
        require_once __DIR__ . '/../../../../aurora/loader.php';
        require_once __DIR__ . '/../../loader.php';

        //'Domains' es un modelo en la app y los modelos usan Env
        require_once __DIR__ . '/../../aliases.php';
        require __DIR__ . '/../../../../aurora/aliases.php';


        $domain = Facade::call('Domains');
        $result = $domain->retrieveRegisterByValue(['name' => 'id', 'value' => "1"]);
        if ($result === null) {
            $I->assertTrue(is_null($result), 'La tabla no existe aun');
        } else {
            $I->assertTrue(!is_null($result), 'Token encontrado' .$result->token);
        }

    }

     /**
     * @group grupo_problem
     */
    public function getById(UnitTester $I){

        require_once __DIR__ . '/../../../../aurora/loader.php';
        require_once __DIR__ . '/../../loader.php';
        require_once __DIR__ . '/../../aliases.php';
        require __DIR__ . '/../../../../aurora/aliases.php';


        $modelsun = Facade::call('MkModelSun');
        
        $modelsun->addFields(['site_name' => 'eliminable.com']);
        $id = $modelsun->store()->id;
        $I->assertIsNumeric($id, 'Se espera id del registro CREADO = '. $id);
        $id = $modelsun->findOrCreate(['site_name = ?',['eliminable.com']])->id;
        $I->assertIsNumeric($id, 'Se espera id del registro ENCONTRADO findorcreate: '. $id);
        
        $id = $modelsun->getById($id)->id;
        $I->assertIsNumeric($id, 'Se espera id del registro ENCONTRADO getById: '. $id);
        $deletedId = $modelsun->deleteById($id);
        $I->assertIsNumeric($deletedId, 'Se espera id del registro ELIMINADO: ' . $id);
    }
    
    public function storeBean(UnitTester $I)
    {

        require_once __DIR__ . '/../../../../aurora/loader.php';
        require_once __DIR__ . '/../../loader.php';
        require_once __DIR__ . '/../../aliases.php';
        require __DIR__ . '/../../../../aurora/aliases.php';

        $m = new MkModelSun(Facade::call('Sentinela'));

        $modelsun = Facade::call('MkModelSun');
        $randomNumber = mt_rand(1000000000, 9999999999);
        // $randomNumber = 9999999999; //TODO para que ?
        $modelsun->addFields(['site_name' => 'www.unitmodelcest.com','id_unico' => $randomNumber]);
        $id =   $modelsun->store()->id;
        $I->assertIsNumeric($id, 'id del registro NUEVO: '. $id);
        

        //Intentar crear otro registro con el mismo id unico, debe fallar

        $modelsun->addFields(['site_name' => 'www.id-repetido.com','id_unico' => $randomNumber]);
        try {
            $id =   $modelsun->store()->id;

        } catch (Throwable $exception) {
            $I->expectThrowable(Exception::class, function () use ($exception, $I) {
                throw $exception;
            }, 'Se espera excepción por intento de insercion  con id unico duplicado');//TODO se debe manejar esta excepcion en el sitema para que no simplemente se pierda el registro
        }

        $randomNumber = mt_rand(1000000000, 9999999999);
        $modelsun->addFields(['site_name' => 'www.unitmodelcest.com','id_unico' => $randomNumber]);

        $id = $modelsun->findOrCreate('site_name')->id;

        $I->assertIsNumeric($id, 'id del registro UPDATE: '. $id);
        
        $condition = ['site_name = ? AND id_unico = ?', ['www.unitmodelcest.com', $randomNumber]];
        $id = $modelsun->findOrCreate($condition)->id;
        
        $I->assertIsNumeric($id, 'id del registro ENCONTRADO: '. $id);
        
        ///////////////////////////////////////
        ///////////////////////////////////////

        $modelsun = Facade::call('MkModelSun');
        $modelsun->addFields(['site_name' => 'invalid_field', 'campo invalido' => 'campo invalido']);
        try {
            $modelsun->store();

        } catch (Throwable $exception) {
            $I->expectThrowable(Exception::class, function () use ($exception, $I) {
                throw $exception;
            }, 'Se espera excepción por intento de insercion  en campo no permitido ("campo invalido") para este modelo');
        }
        
    }
   
}
