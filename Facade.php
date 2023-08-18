<?php

//Este artefacto es muy util para instanciar o referirse a clases de la aplicacion de manera dinamica, las cuales no estan en el ambito del core ya que el core lopueden consumir diversas aplicaciones 

namespace DgbAuroCore\vendor\Inventarium;

use DgbAuroCore\vendor\Inventarium\Factory;

class Facade
{
    private static $eventObservers = [];

    public static $aliases = array();

    public static function addAlias($alias, $class,$override = true)
    {
        //TODO esto del override me hizo fallar casi todos los test porque no inclui el parametro override ya que lo puse despues, es buena idea como prevención pero debo reflexionar mas como implementarlo .. ahiora tengo sueño
        if (isset(self::$aliases[$alias]) && !$override) {
            // La variable en el arreglo está definida
            throw new \Exception('No se permiten explicitamente sobreescribir los alias, use el tercer parametro (override) en true en la llamada .');
        } 
        self::$aliases[$alias] = $class;
    }

    public static function call($className, $params = [])
    {
        $factory = new Factory(self::$aliases);

        return  $factory->build($className, $params);
    }


    public static function trigger($e)
    {
        $eventName = $e['event'];

        // Verifica si hay observadores registrados para el evento especificado
        if (isset(self::$eventObservers[$eventName])) {
            $observers = self::$eventObservers[$eventName];

            foreach ($observers as $observer) {
                $observer->$eventName($e);
            }
        }
    }

    public static function delegateEvent($obj, $event)
    {
        // Utiliza el array asociativo para almacenar los observadores para cada evento
        if (!isset(self::$eventObservers[$event])) {
            self::$eventObservers[$event] = [];
        }

        array_push(self::$eventObservers[$event], $obj);
    }
}
