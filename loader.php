<?php

spl_autoload_register(function ($class) {
    $dgb_appNamespace = 'DgbAuroCore\\';
    $base_dir = dirname(dirname(__DIR__)) . '/';

    if (strpos($class, $dgb_appNamespace) === 0) {
        $relative_class = substr($class, strlen($dgb_appNamespace));
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        // dgbdd($file); //TODO deberia haber un sistema de debug para este tipo de casos y no estar habilitando deshabilitando impresiones
        if (!file_exists($file)) {
            $subdirectories = explode('\\', $relative_class);
            $filename = array_pop($subdirectories);
            $subdirectory_path = implode('/', $subdirectories);

            $file = $base_dir . $subdirectory_path . '/' . $filename . '.php';
        }

        if (file_exists($file)) {
            require $file;
        }
    }
});
