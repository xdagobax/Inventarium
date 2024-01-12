<?php
//TODO debo crear una clase especializad en manejar el debuggin , por ejemp0lo para hacer debuggin selectivo del router o del loader.
namespace DgbAuroCore\lib\Inventarium;

use DgbAuroCore\lib\Inventarium\Facade;

class Router
{

	protected $requestUri;
	protected $routes = [];
	private $env;

	public function __construct($requestUri)
	{

		// XXX ¿Y si mejor uso esto:  $ruta = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		//TODO aceptar url de guarda (vacia y que siempre conduzca e.g. al home)

		$this->env = Facade::call('Env'); //Variables de enotrno del folder y el estado del debug mode (true/false)

		// Dividir la URI en segmentos
		$segments = explode('/', trim($requestUri, '/'));

		$folder = str_replace('/','',$this->env::env('FOLDER'));
		// Encontrar el índice de la carpeta 'DgbTools'
		$index = array_search($folder, $segments);

		// Verificar si se encontró la carpeta 'DgbTools'
		if ($index !== false) {
			// Obtener la porción de la URI a partir de la carpeta 'DgbTools'
			$this->requestUri = '/' . implode('/', array_slice($segments, $index)).'/';
			$this->requestUri = rawurldecode(preg_replace('#/+#', '/', $this->requestUri)); //TODO creo que es por si hay dobles slash (//)
			} else {
				// echo('No existe la rutal '. $this->requestUri );
				// Si no se encuentra la carpeta 'DgbTools', usar la URI completa
				$this->requestUri = rawurldecode(preg_replace('#/+#', '/', $requestUri)); //TODO creo que es por si hay dobles slash (//)
			// $this->requestUri = $requestUri;
		}


	}

	public function add($uri, $closure, $classParams = [], $queryparams = false)
	{

		if (!$queryparams) {

			$this->requestUri = strtok($this->requestUri, '?');
		}

		$uri = $this->env::env('FOLDER') . $uri;

		$this->env::env('DEBUG') ? $this->debug($uri) : false;

		$route = new Route($uri, $closure, $classParams);
		array_push($this->routes, $route);
	}

	private function debug($uri)
	{
		$this->env::env('DEBUG') ? dgbec("SERVER REQUEST_URI: <br>" . $this->requestUri) : false;
		$this->env::env('DEBUG') ? dgbec("env folder: <br>" . $uri) : false;
	}

	public function run()
	{

		$response = false;

		foreach ($this->routes as $route) {

			if ($route->checkIfMatch($this->requestUri)) {

				$response = $route->execute();

				// break para no seguir dando vueltas
				// Ya se encontró la ruta correspondiente
				break;
			}
		}

		return $this->sendResponse($response);
	}


	public function sendResponse($response)
	{
		if (is_string($response)) {
			echo  $response;
		} else if (is_array($response)) {
			echo  json_encode($response);
		} else if ($response instanceof \Response) {
			echo  $response->execute();
		} else if (is_int($response)) {
			echo  strval($response);
		} else if ($response === true) {
			http_response_code(200);
		} else {


			if (function_exists('dgbInTest')) {

				header("HTTP/1.0 404 Not Found");
				return '404';
			} else {

				header("HTTP/1.0 404 Not Found");
				exit('404');
			}
		}
	}
}
