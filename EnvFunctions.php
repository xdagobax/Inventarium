<?php

namespace DgbAuroCore\vendor\Inventarium;


// function env($key, $default = null)
// {
//     return EnvFunctions::env($key, $default);
// }

class EnvFunctions
{
    public static $initready = false;
    protected static $env_vars = [];
    
    public static function getVars()
    {
        return self::$env_vars;
    }

    public static function env($key, $default = null)
    {
        $value = self::$initready == true ? true : (function ()  {
            Facade::call('Env')::init();
            return;
            // throw new \Exception('Falta inicializar Facade::call(\'Env\')::init();');
        })();
        
        $value =isset(self::getVars()[$key]) ? isset(self::getVars()[$key]) : (function () use ($key) {
            Facade::call('Env')::init();

            $value =isset(self::getVars()[$key]) ? isset(self::getVars()[$key]) : (function () use ($key) {
    
                throw new \Exception("La clave de Env '$key' no existe");
            })();
            return $value;
        })();
        
        $value = self::getVars()[$key] ?? $default;
        return $value;
    }

    public static function set($key, $value)
    {
        $value = self::$env_vars[$key] = $value;
    }
}
