<?php

namespace DgbAuroCore\lib\Inventarium\tests\mockups;

use DgbAuroCore\lib\Inventarium\Controller;
use DgbAuroCore\lib\Inventarium\Model;

//Probar si se inyectan las clases con la factoria
class TestFactory
{

    public $model; 
    public $controller; 

    public function __construct(Controller $controller, Model $model)
    {

      
        $this->model = $model;
        $this->controller = $controller;

    }
   
}
