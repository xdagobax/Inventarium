<?php

use DgbAuroCore\vendor\Inventarium\tests\mockups\MkModelSun;
use DgbAuroCore\vendor\Inventarium\Facade;
use TestCore\UnitTester;

class ModelCest
{
    function __construct()
    {
        require_once __DIR__ . '../../../../dgbdebugger/debug.php';
    }

    
    public function redBean(UnitTester $I)
    {
        
        require_once __DIR__ . '/../../loader.php';
        require_once __DIR__ . '/../../aliases.php';

        //Tengo que usar estos requires porque el constructor de los modelos (Model) usa Env
        require_once __DIR__ . '/../../../../../aurora/loader.php';
        require __DIR__ . '/../../../../../aurora/aliases.php';


        $modelsun = new MkModelSun();
    }

    //TODO este test prueba la app cliente ... No deberiamos usar un mockup o probar directo la clase modelo del core ?
    public function retrieveRegisterByValue(UnitTester $I){
        require_once __DIR__ . '/../../../../../aurora/loader.php';
        require_once __DIR__ . '/../../loader.php';

        //'Domains' es un modelo en la app y los modelos usan Env
        require_once __DIR__ . '/../../aliases.php';
        require __DIR__ . '/../../../../../aurora/aliases.php';


        $domain = Facade::call('Domains');
        $result = $domain->retrieveRegisterByValue(['name' => 'id', 'value' => "1"]);
        $I->assertTrue(!is_null($result), 'Token encontrado' .$result->token);

    }

    public function getById(UnitTester $I){

        require_once __DIR__ . '/../../../../../aurora/loader.php';
        require_once __DIR__ . '/../../loader.php';
        require_once __DIR__ . '/../../aliases.php';
        require __DIR__ . '/../../../../../aurora/aliases.php';


        $modelsun = Facade::call('MkModelSun');
        
        $modelsun->addFields(['name' => 'eliminable.com']);
        $id = $modelsun->store()->id;
        $I->assertIsNumeric($id, 'Se espera id del registro CREADO = '. $id);
        $id = $modelsun->findOrCreate(['name = ?',['eliminable.com']])->id;
        $I->assertIsNumeric($id, 'Se espera id del registro ENCONTRADO findorcreate: '. $id);
        
        $id = $modelsun->getById($id)->id;
        $I->assertIsNumeric($id, 'Se espera id del registro ENCONTRADO getById: '. $id);
        $deletedId = $modelsun->deleteById($id);
        $I->assertIsNumeric($deletedId, 'Se espera id del registro ELIMINADO: ' . $id);
    }
    
    public function storeBean(UnitTester $I)
    {

        require_once __DIR__ . '/../../../../../aurora/loader.php';
        require_once __DIR__ . '/../../loader.php';
        require_once __DIR__ . '/../../aliases.php';
        require __DIR__ . '/../../../../../aurora/aliases.php';

        $m = new MkModelSun(Facade::call('Sentinela'));

        $modelsun = Facade::call('MkModelSun');
        $randomNumber = mt_rand(1000000000, 9999999999);
        // $randomNumber = 9999999999; //TODO para que ?
        $modelsun->addFields(['name' => 'www.unitmodelcest.com','id_unico' => $randomNumber]);
        $id =   $modelsun->store()->id;
        $I->assertIsNumeric($id, 'id del registro NUEVO: '. $id);
        
        $randomNumber = mt_rand(1000000000, 9999999999);
        $modelsun->addFields(['name' => 'www.unitmodelcest.com','id_unico' => $randomNumber]);

        $id = $modelsun->findOrCreate('name')->id;

        $I->assertIsNumeric($id, 'id del registro UPDATE: '. $id);
        
        $condition = ['name = ? AND id_unico = ?', ['www.unitmodelcest.com', $randomNumber]];
        $id = $modelsun->findOrCreate($condition)->id;
        
        $I->assertIsNumeric($id, 'id del registro ENCONTRADO: '. $id);
        
        ///////////////////////////////////////
        ///////////////////////////////////////

        $modelsun = Facade::call('MkModelSun');
        $modelsun->addFields(['name' => 'invalid_field', 'campo invalido' => 'campo invalido']);
        try {
            $modelsun->store();

        } catch (Throwable $exception) {
            $I->expectThrowable(Exception::class, function () use ($exception, $I) {
                throw $exception;
            }, 'Se espera excepción por registro en campo no permitido para este modelo');
        }
        
    }
   
}
