<?php

namespace DgbAuroCore\vendor\Inventarium;

use DgbAuroCore\vendor\Inventarium\Facade;

class Route {

	protected $uri;
	protected $closure;
	protected $classParams;

	const PARAMETER_PATTERN = '/:([^\/]+)/';
	const PARAMETER_REPLACEMENT = '(?<\1>[^/]+)';
	protected $parameters;

	//TODO $closure puede ser un array o una funcion anonima lo que me parece confuso
	//Quizas siempre debería ser una funcion anonima que ya este por ahi implementada para solo usarla, pero eso hara más compleja la llamada
	public function __construct($uri, $closure,$classParams)
	{
		$this->uri = $uri;
		$this->closure = $closure;
		$this->classParams = $classParams;
	}

	public function getUriPattern()
	{
		$uriPattern = preg_replace(self::PARAMETER_PATTERN, self::PARAMETER_REPLACEMENT, $this->uri);
		$uriPattern = str_replace('/', '\/', $uriPattern);
		$uriPattern = '/^' . $uriPattern . '\/*$/s';
		return $uriPattern;
	}

	public function getParameterNames()
	{
		preg_match_all(self::PARAMETER_PATTERN, $this->uri, $parameterNames);
		return array_flip($parameterNames[1]);
	}

	public function resolveParameters($matches)
	{
		$this->parameters = array_intersect_key($matches, $this->getParameterNames());
	}

	public function getParameters()
	{
		return $this->parameters;
	}

	public function checkIfMatch($requestUri)
	{

		$uriPattern = $this->getUriPattern();

		if (preg_match($uriPattern, $requestUri, $matches))
		{
			$this->resolveParameters($matches);
			return true;
		}
		return false;
	}

	public function execute()
	{
		$closure = $this->closure;
		if(is_array($closure)){
			
			//Construccion de la clase con parametros si los hay en su constructor, si no tiene simplemente php los ignora
			$useClass =  Facade::call($closure[0],$this->classParams);

			//Se asigna a una variable el valor de $closure[1] que contiene el metodo ya que sino php no lo covierte implicitamente para ejecucion y lo sigue considerando un array  
			$methodName = $closure[1];
			return $useClass->$methodName();
		}

		$parameters = $this->getParameters();
		return call_user_func_array($closure, $parameters);
	}

}
