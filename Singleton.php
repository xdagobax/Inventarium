<?php

namespace DgbAuroCore\vendor\Inventarium;


class Singleton
{

	public static $instancesHere = array();

	protected function __construct()
	{
		//TODO ¿Por que esta el constructor vacio?
	}

	final public static function create(...$args)
    {
        $calledClass = get_called_class();

        if (!isset(self::$instancesHere[$calledClass])) {
            self::$instancesHere[$calledClass] = new $calledClass(...$args);
        }

        return self::$instancesHere[$calledClass];
    }

	final public static function remove($class)
	{


		if (isset(self::$instancesHere[$class])) {
			unset(self::$instancesHere[$class]);
		}
	}


	//TODO porque esta _clone vacio ?
	private function __clone()
	{
	}
}
