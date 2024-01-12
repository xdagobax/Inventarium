<?php

use DgbAuroCore\lib\Inventarium\Facade;

$clase = 'DgbAuroCore\lib\Inventarium\Facade';

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
Facade::addAlias('Cnx', 'DgbAuroCore\lib\Inventarium\Cnx');
Facade::addAlias('DgbAuroCore\lib\Inventarium\interfaces\SessionManagerInterface', 'DgbAuroCore\lib\Inventarium\Sentinela');
Facade::addAlias('Sentinela', 'DgbAuroCore\lib\Inventarium\Sentinela');
Facade::addAlias('Util', 'DgbAuroCore\lib\Inventarium\Util');
Facade::addAlias('Render', 'DgbAuroCore\lib\Inventarium\Render');

//TEST
Facade::addAlias('WithConstructParams', 'DgbAuroCore\lib\Inventarium\tests\mockups\WithConstructParams');
Facade::addAlias('MkModelSun', 'DgbAuroCore\lib\Inventarium\tests\mockups\MkModelSun');
Facade::addAlias('MkClass', 'DgbAuroCore\lib\Inventarium\tests\mockups\MkClass');
Facade::addAlias('EventSender', 'DgbAuroCore\lib\Inventarium\tests\mockups\EventSender');
Facade::addAlias('EventSuscriptor', 'DgbAuroCore\lib\Inventarium\tests\mockups\EventSuscriptor');
Facade::addAlias('EventSuscriptorTwo', 'DgbAuroCore\lib\Inventarium\tests\mockups\EventSuscriptorTwo');
Facade::addAlias('HookSubject', 'DgbAuroCore\lib\Inventarium\tests\mockups\HookSubject');
Facade::addAlias('Env', 'DgbAuroCore\lib\Inventarium\tests\mockups\EnvMockup');
