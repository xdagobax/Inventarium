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
