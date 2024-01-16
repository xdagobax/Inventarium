<?php

namespace DgbAuroCore\vendor\Inventarium\tests\mockups;

use DgbAuroCore\vendor\Inventarium\Controller;
use DgbAuroCore\vendor\Inventarium\Model;

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
