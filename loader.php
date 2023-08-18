<?php

spl_autoload_register(function ($class) {
    $dgb_appNamespace = 'DgbAuroCore\\';
    $base_dir = dirname(dirname(__DIR__)) . '/';

    // if (strpos($class, 'Facade') === false && strpos($class, 'Factory') === false) {
    //     dgbdd($class);
    //     }
    if (strpos($class, $dgb_appNamespace) === 0) {
        $relative_class = substr($class, strlen($dgb_appNamespace));
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        // if (strpos($file, 'Facade') === false && strpos($file, 'Factory') === false) {
        // dgbdd($file);
        // }
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
