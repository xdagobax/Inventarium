<?php

use DgbAuroCore\vendor\Inventarium\Facade;

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
