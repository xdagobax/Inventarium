<?php

namespace DgbAuroCore\lib\Inventarium\tests\mockups;
use DgbAuroCore\lib\Inventarium\EnvFunctions;


// function env($key, $default = null)
// {
//     return EnvFunctions::env($key, $default);
// }

class EnvMockup extends EnvFunctions
{
    // private static $env_vars = [];
    public static function init()
    {
        EnvFunctions::$env_vars = [
            'DEBUG' => false,
            'AURORA_PATH' => __DIR__,
            'DBHOST' => 'localhost',
            'DBNAME' => 'auroradb',
            'DBUSER' => '-dgbDeveloper33#',
            'DBPASS' => '-dgbDeveloper33#pass',
            // 'DBUSER' => 'igualifyuser',
            // 'DBPASS' => '@Igualify33',
            'PREFIX' => 'aura',
            'QUICK_TEST' => false,
            'BUSY_TIME' => 10,
            'ROOT' => __DIR__,
            'URL' => 'http://localhost/aurora',
            'APP_NAME' => 'Aurora',
        ];
        EnvFunctions::$initready = true;
    }
    // public static function getVars()
    // {
    //     return EnvFunctions::$env_vars;
    // }

    // public static function env($key, $default = null)
    // {
    //     return EnvFunctions::env($key, $default);
    // }

    // public static function set($key, $value)
    // {
    //     $value = EnvFunctions::$env_vars[$key] = $value;
    // }
}
