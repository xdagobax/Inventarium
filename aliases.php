<?php
//TODO y el namespace?
use DgbAuroCore\vendor\Inventarium\Facade;

$clase = 'DgbAuroCore\vendor\Inventarium\Facade';

//TODO esto debería estandarizarse pero entonces heredar todos los sliases de una clase del core
// Comprobar si la clase está disponible
if (!class_exists($clase)) {

    $stackTrace = debug_backtrace();
    
    echo "Pila de llamadas:\n";
    
    foreach ($stackTrace as $index => $call) {
        if ($index > 0) {
            echo "#{$index} ";
            if (isset($call['class'])) {
                echo "{$call['class']}{$call['type']}";
            }
            echo "{$call['function']}()\n";
        }
    }
    // La clase está disponible, puedes utilizarla
    throw new \Exception("aliases Err: No se encuentra la clase Facade, primero debes requerir el loader");
}

//CORE
Facade::addAlias('Cnx', 'DgbAuroCore\vendor\Inventarium\Cnx');
Facade::addAlias('DgbAuroCore\vendor\Inventarium\interfaces\SessionManagerInterface', 'DgbAuroCore\vendor\Inventarium\Sentinela');
Facade::addAlias('Sentinela', 'DgbAuroCore\vendor\Inventarium\Sentinela');
Facade::addAlias('Util', 'DgbAuroCore\vendor\Inventarium\Util');
Facade::addAlias('Render', 'DgbAuroCore\vendor\Inventarium\Render');

//TODO con una clase se podria gestionar que los aliases de test se definan en otra parte y que se carguen solo en test
//TEST
Facade::addAlias('WithConstructParams', 'DgbAuroCore\vendor\Inventarium\tests\mockups\WithConstructParams');
Facade::addAlias('MkModelSun', 'DgbAuroCore\vendor\Inventarium\tests\mockups\MkModelSun');
Facade::addAlias('MkClass', 'DgbAuroCore\vendor\Inventarium\tests\mockups\MkClass');
Facade::addAlias('EventSender', 'DgbAuroCore\vendor\Inventarium\tests\mockups\EventSender');
Facade::addAlias('EventSuscriptor', 'DgbAuroCore\vendor\Inventarium\tests\mockups\EventSuscriptor');
Facade::addAlias('EventSuscriptorTwo', 'DgbAuroCore\vendor\Inventarium\tests\mockups\EventSuscriptorTwo');
Facade::addAlias('HookSubject', 'DgbAuroCore\vendor\Inventarium\tests\mockups\HookSubject');
Facade::addAlias('Env', 'DgbAuroCore\vendor\Inventarium\tests\mockups\EnvMockup');
