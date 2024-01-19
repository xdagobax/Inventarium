<?php


// Verificar la clave secreta

$secret = '@Privado33'; // Reemplaza con tu clave secreta

$webhookContent = file_get_contents('php://input');

$hash = hash_hmac('sha256', $webhookContent, $secret);

// Configurar nombre y correo electrónico si no están configurados

shell_exec('git config user.name "Daniel Badillo"');

shell_exec('git config user.email "emmanaorg@gmail.com"');



// Agregar archivos al área de preparación (stage)

// $output = shell_exec('git add webhook.php 2>&1');

// file_put_contents('git_log.txt', $output, FILE_APPEND);

// echo $output;



// Hacer commit del archivo archivos

// $output = shell_exec('git commit -m "Commit automático de webhook.php antes de la fusión" 2>&1');

// file_put_contents('git_log.txt', $output, FILE_APPEND);

// echo $output;



// Realizar la fusión con la estrategia "theirs"

$output = shell_exec('git pull -X theirs origin main 2>&1');

file_put_contents('git_log.txt', $output, FILE_APPEND);

echo $output;

// if ($_SERVER['HTTP_X_HUB_SIGNATURE'] == 'sha256=' . $hash) {

//     // La firma es válida, continuar con la actualización

//     $output = shell_exec('git pull origin main');

//     echo $output;

// } else {

//     // La firma no es válida, solicitud no autorizada

//     header('HTTP/1.1 401 Unauthorized');

//     echo 'Unauthorized dgb';

// }



