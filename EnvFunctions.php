<?php

namespace DgbAuroCore\vendor\Inventarium;


class EnvFunctions
{
    public static $initready = false;//TODO para que sirve? Eliminar?
    protected static $env_vars = [];
    private static $user_setted_env_vars = [];

    public static function getVars()
    {
        //TODO ¿Debería el folder definirse en un archivo de cliente como el de configuracion? Lo puse aqui originalmenteporque me molesta tener algoritmos en el archivo Env el cual debería ser exclusivo para declaraciones de las variables de entorno
        self::$env_vars['FOLDER'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', self::$env_vars['ROOT']));
        self::$env_vars = array_merge(self::$env_vars, self::$user_setted_env_vars);
        return self::$env_vars;
    }

    public static function env($key, $default = null)
    {
        //TODO creo que no deberia usarse una variable estatica como flag porque puede ocasionar colisiones o falsos init ready cuando se requiere reiniciar de nuevo en carga de nuevo archivo Env, una posible solucion sería renicializarlo ante cada carga de nuevo archivo Env para que empiece de nuevo
        $value = self::$initready == true ? true : (function () {
            Facade::call('Env')::init();
            return;
        })();

        $value = isset(self::getVars()[$key]) ? isset(self::getVars()[$key]) : (function () use ($key) {
            Facade::call('Env')::init();
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
