<?php

namespace DgbAuroCore\lib\Inventarium;


// function env($key, $default = null)
// {
//     return EnvFunctions::env($key, $default);
// }

class EnvFunctions
{
    public static $initready = false;
    protected static $env_vars = [];
    private static $user_setted_env_vars = [];

    public static function getVars()
    {
        self::$env_vars['FOLDER'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', self::$env_vars['ROOT']));
        self::$env_vars = array_merge(self::$env_vars, self::$user_setted_env_vars);
        return self::$env_vars;
    }

    public static function env($key, $default = null)
    {
        $value = self::$initready == true ? true : (function () {
            Facade::call('Env')::init();
            // self::$env_vars = array_merge(self::$env_vars, self::$user_setted_env_vars);

            return;
            // throw new \Exception('Falta inicializar Facade::call(\'Env\')::init();');
        })();

        $value = isset(self::getVars()[$key]) ? isset(self::getVars()[$key]) : (function () use ($key) {
            Facade::call('Env')::init();
            // var_dump(self::$env_vars);
            // var_dump(self::$user_setted_env_vars);
            // self::$env_vars = array_merge(self::$env_vars, self::$user_setted_env_vars);
            // var_dump(self::$env_vars);
            // die();
            $value = isset(self::getVars()[$key]) ? isset(self::getVars()[$key]) : (function () use ($key) {

                throw new \Exception("La clave de Env '$key' no existe");
            })();
            return $value;
        })();

        $value = self::getVars()[$key] ?? $default;
        return $value;
    }

    public static function set($key, $value, $override = false)
    {
        if (!$override) {
            throw new \Exception("La clave de Env '$key' no se puede sobreescribir implicitamente, defina el tercer parametr (override) a true ");
        }
        self::$user_setted_env_vars[$key] = $value;
    }
}
