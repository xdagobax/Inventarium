<?php

//Este artefacto es muy util para instanciar o referirse a clases de la aplicacion de manera dinamica, las cuales no estan en el ambito del core ya que el core lo pueden consumir diversas aplicaciones 


namespace DgbAuroCore\vendor\Inventarium;

use DgbAuroCore\vendor\Inventarium\Factory;


function compararPorPrioridad($a, $b)
{
    // Compara por prioridad, de mayor a menor
    return $b[1] - $a[1];
}



class Facade
{
    private static $hookObservers = [];
    private static $eventObservers = [];
    private static $eventResponses = [];

    public static $aliases = array();

    public static function getEventResponses()
    {
        return self::$eventResponses;
    }

    public static function addAlias($alias, $class, $override = false)
    {


        //En los test cargo diversos archivos alias, en produccion las apps deben cargar solo su propio archivo alias o hacer el override explicitamente, no hacerlo genera resultados inesperados, sobre todo al asignar el alias de Env
        //TODO de esto no tengo ninguna prueba automatizada ni escenarios
        if (function_exists('dgbInTest')) {

            $override = true;
        }


        if (isset(self::$aliases[$alias]) && !$override) {
            // La variable en el arreglo está definida
            throw new \Exception('No se permiten explicitamente sobreescribir los alias, use el tercer parametro (override) en true en la llamada : ' . $alias);
        }
        self::$aliases[$alias] = $class;
    }

    public static function getAliasStr($alias)
    {

        if (isset(self::$aliases[$alias])) {
            return self::$aliases[$alias];
        }
    }
    
    public static function removeAllAlias()
    {
        self::$aliases = array();
    }

    public static function call($className, $params = [])
    {
        $factory = new Factory(self::$aliases);

        return  $factory->build($className, $params);
    }


    //TODO esta clase hace mas de una cosa, pero ... es global por eso es así ¿Conviene dividirla en mas clases ? Quizas si y una global que incluya a todas las que quiera que sean globales 
    public static function trigger($e)
    {

        $eventName = $e['event'];

        // Verifica si hay observadores registrados para el evento especificado
        if (isset(self::$eventObservers[$eventName])) {
            $eventObservers = self::$eventObservers[$eventName];


            usort($eventObservers, 'DgbAuroCore\vendor\Inventarium\compararPorPrioridad');

            foreach ($eventObservers as $observer) {

                if (isset($e['response'])) {

                    unset($e['response']);
                }

                //TODO ¿Hay test de esto?
                $response = $observer[0]->$eventName($e); //Se envia el argumento que se implemento en la llamada completo y ya cada receptor ve como lo maneja, el primer elemento es el nombre del evento , pero el segundo elemento a veces tendra un array en args, a veces un valor simple y a veces no habra args y su nombre puede ser cualquiera.

                $e['suscriptor'] = get_class($observer[0]);
                $e['response'] = $response;
                $observer[0]->event = $e;

                $clonedObject = clone $observer[0];
                array_push(self::$eventResponses, $clonedObject);
            }

        }
    }

    public static function delegateEvent($obj, $event, $prioriti = 10)
    {

        // TODO será buena idea asignar una prioridad y un numero de argumentos tal como hace wordpress? Y tambien dividir en acciones y filtros?
        // Utiliza el array asociativo para almacenar los observadores para cada evento
        if (!isset(self::$eventObservers[$event])) {
            self::$eventObservers[$event] = [];
        }
        // 
        array_push(self::$eventObservers[$event], [$obj, $prioriti]);
    }

    //XXX faltan test para esto, si estan pero en la clase hookCest hace falta pasarlos a facadeCest
    public static function doAction($hookName, ...$args)
    {

        //TODO solo hace exactamente lo mismo que applyFilter??
        return self::applyFilter($hookName, ...$args);
    }

    //XXX Faltan test que ilustren esta funcion con sus argumentos en UpdateControllerCest hay una implementacion con argumentos
    public static function applyFilter($hookName, $initialValue = null, ...$args)
    {

        if (!isset(self::$hookObservers[$hookName])) {
            return $initialValue;
        }

        //TODO que tal definir algunos eventos por aqui y por alla para que los test puedan suscribirse y recuperar valores que no da el return?  valores puntuales por ejemplo de un momento de la ejecucion de la funcioon en especifico 

        $filteredValue = $initialValue;

        foreach (self::$hookObservers[$hookName] as $hookFunction) {
            if (is_callable($hookFunction[0])) {

                //PHP 7.4.X debería ser 
                // $filteredValue = call_user_func_array($hookFunction[0], [$filteredValue, ...$args]);
                //TODO implementar archivos para soporte entre versiones
                //Soporte PHP para versiones < 7.4 
                $filteredValue = call_user_func_array($hookFunction[0], array_merge([$filteredValue], $args));
            }
        }

        return $filteredValue;
    }


    //Simplemente es un wrap ? para que?
    public static function addAction($hookName, $fn = null, $prioriti = 10)
    {
        self::addFilter($hookName, $fn, $prioriti);
    }

//TODO cual es la prioridad mas alta 10 o 0?
    public static function addFilter($hookName, $fn = null, $priority = 10)
    {
        if (!isset(self::$hookObservers[$hookName])) {
            self::$hookObservers[$hookName] = [];
        }

        if (is_callable($fn)) {
            // Agregar la función de filtro con su prioridad
            self::$hookObservers[$hookName][] = [$fn, $priority];

            // Ordenar las funciones de filtro por prioridad
            usort(self::$hookObservers[$hookName], function ($a, $b) {
                return $b[1] - $a[1];
            });
        }
    }

    public static function removeFilter($hookName)
    {
        if (isset(self::$hookObservers[$hookName])) {
            unset(self::$hookObservers[$hookName]);
        }
    }

    //TODO en las pruebasa es necesario elminar eventos previos .. pero ¿Como identificar cuando sea necesario ? Y mas importante aun ¿Como prevenir que se me olvide?
    //TODO remover los eventos puede ser peligrosos ¿Que tal solo suspender hasta siguiente llamada?

    public static function removeAllEvent()
    {
        if (isset(self::$eventObservers)) {
            self::$eventObservers  = [];
        }
        if (isset(self::$eventResponses)) {
            self::$eventResponses  = [];
        }
    }
}
