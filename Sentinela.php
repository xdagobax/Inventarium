<?php

namespace DgbAuroCore\vendor\Inventarium;

use DgbAuroCore\vendor\Inventarium\interfaces\SessionManagerInterface;


class Sentinela extends Singleton implements SessionManagerInterface

{

    public function __construct() {
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Actualizar la actividad de la sesión
        // $this->updateActivity();
    }
    
    public function set($key, $value) {
        // Establecer un valor en la sesión
        $_SESSION[$key] = $value;
    }
    
    public function get($key) {
        // Obtener un valor de la sesión
        return $_SESSION[$key] ?? null;
    }
    
    // public function exists($key) {
    //     // Comprobar si un valor existe en la sesión
    //     return isset($_SESSION[$key]);
    // }
    
    // public function updateActivity() {
    //     // Actualizar la actividad de la sesión
    //     $_SESSION['last_activity'] = time();
    // }
    
    // public function checkActivity($timeout) {
    //     // Comprobar la actividad de la sesión
    //     if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    //         // La sesión ha expirado, destruirla
    //         $this->close();
    //     } else {
    //         // Actualizar la actividad de la sesión
    //         $this->updateActivity();
    //     }
    // }
    
    // public function close() {
    //     // Cerrar la sesión
    //     session_unset();
    //     session_destroy();
    // }

}
