<?php

namespace DgbAuroCore\vendor\Inventarium;

use DgbAuroCore\vendor\Inventarium\interfaces\SessionManagerInterface;


class Sentinela extends Singleton implements SessionManagerInterface

{

    public function __construct() {
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
    }
    
    public function set($key, $value) {
        // Establecer un valor en la sesión
        $_SESSION[$key] = $value;
    }
    
    public function get($key) {
        // Obtener un valor de la sesión
        return $_SESSION[$key] ?? null;
    }

}