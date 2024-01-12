<?php

namespace DgbAuroCore\lib\Inventarium;

use DgbAuroCore\lib\Inventarium\Facade;


//TODO de momento no hago nada con esta clase pero siento feo eliminarla, probablemente la use para realizar operaciones con comandos sql en los cms donde no puedo usar redBean
class Cnx extends Singleton

{

    public function __construct()
    {
    }

  

    private function dgbmysqlinit()
    {

        // $mysqli = new \mysqli(DGB_DB_HOST, DGB_DB_USER, DGB_DB_PASSWORD, DGB_DB_NAME);
        define('DGB_DB_HOST', 'localhost');
        define('DGB_DB_USER', 'matho_2022');
        define('DGB_DB_PASSWORD', 'admin001232022');
        define('DGB_DB_NAME', 'matho_2022d');
    }
}

if (!isset($dgbCnx)) {
    // $dgbCnx = Facade::call('Cnx');
}
