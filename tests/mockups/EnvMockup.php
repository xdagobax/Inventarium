<?php

namespace DgbAuroCore\vendor\Inventarium\tests\mockups;
use DgbAuroCore\vendor\Inventarium\EnvFunctions;

class EnvMockup extends EnvFunctions
{
    public static function init()
    {
        EnvFunctions::$env_vars = [
            'DEBUG' => false,
            'AURORA_PATH' => __DIR__,
            'DBHOST' => 'localhost',
            'DBNAME' => 'auroradb',
            'DBUSER' => '-dgbDeveloper33#',
            'DBPASS' => '-dgbDeveloper33#pass',
            'PREFIX' => 'aura',
            'QUICK_TEST' => false,
            'BUSY_TIME' => 10,
            'ROOT' => __DIR__,
            'URL' => 'http://localhost/aurora',
            'APP_NAME' => 'Aurora',
        ];
        EnvFunctions::$initready = true;
    }
    
}
