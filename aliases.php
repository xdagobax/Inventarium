<?php

use DgbAuroCore\vendor\Inventarium\Facade;

$clase = 'DgbAuroCore\vendor\Inventarium\Facade';

// Comprobar si la clase está disponible
if (!class_exists($clase)) {
    // La clase está disponible, puedes utilizarla
    throw new \Exception("aliases Err: No se encuentra la clase Facade, primero debes requerir el loader");
}

//CORE
Facade::addAlias('Cnx', 'DgbAuroCore\vendor\Inventarium\Cnx');
Facade::addAlias('DgbAuroCore\vendor\Inventarium\interfaces\SessionManagerInterface', 'DgbAuroCore\vendor\Inventarium\Sentinela');
Facade::addAlias('Sentinela', 'DgbAuroCore\vendor\Inventarium\Sentinela');
Facade::addAlias('Util', 'DgbAuroCore\vendor\Inventarium\Util');
Facade::addAlias('Render', 'DgbAuroCore\vendor\Inventarium\Render');

//TEST
Facade::addAlias('WithConstructParams', 'DgbAuroCore\vendor\Inventarium\tests\mockups\WithConstructParams');
Facade::addAlias('MkModelSun', 'DgbAuroCore\vendor\Inventarium\tests\mockups\MkModelSun');
Facade::addAlias('MkClass', 'DgbAuroCore\vendor\Inventarium\tests\mockups\MkClass');
Facade::addAlias('EventSender', 'DgbAuroCore\vendor\Inventarium\tests\mockups\EventSender');
Facade::addAlias('EventSuscriptor', 'DgbAuroCore\vendor\Inventarium\tests\mockups\EventSuscriptor');
Facade::addAlias('EventSuscriptorTwo', 'DgbAuroCore\vendor\Inventarium\tests\mockups\EventSuscriptorTwo');
Facade::addAlias('HookSubject', 'DgbAuroCore\vendor\Inventarium\tests\mockups\HookSubject');
Facade::addAlias('Env', 'DgbAuroCore\vendor\Inventarium\tests\mockups\EnvMockup');
