<?php

namespace DgbAuroCore\vendor\Inventarium;

class Util
{


    public function tryCURL($params = [])
    {

        //Ejemplo de llamada:
        // $fullUrl = 'http://localhost/aurora/public/api/post/start';
        // $data = [
        //     'url' => 'http://localhost/aurora/public/',
        //     'secretKey' => '1234567890',
        // ];
        // $response = $this->util->tryCURL([$fullUrl, $data], $I);

        $url = $params[0];
        $data = $params[1];
        $headers = array(
            'Content-Type: application/json',
        );

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
        );

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $responseData = dgbJsonDecode($response, true);
        if ($responseData === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("La respuesta no es un JSON válido: " . json_last_error_msg());
        }
        // Manejar la respuesta
        if ($responseData) {

            return [$httpCode,$responseData];
        } else {
            var_dump($response);
            throw new \Exception('Hay algun tipo de error en la respuesta ');


        }
    }

    public function tryBrowser($url)
    {


        // Inicializar cURL
        $curl = curl_init($url);

        // Establecer opciones cURL
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36');

        // Ejecutar la solicitud y obtener la respuesta
        $response = curl_exec($curl);

        // Verificar si hay errores
        if ($response === false) {
            $error = curl_error($curl);
            //TODO mejro un return con array descriptivo que un echo?
            echo "Error en la solicitud: " . $error;
        } 

        // Cerrar la conexión cURL
        curl_close($curl);
        return $response;
    }

    public function dgbJsonDecode($json, $assoc = false, $depth = 512, $options = 0)
    {
        $decoded = json_decode($json, $assoc, $depth, $options);
        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            echo $json;
            throw new \Exception('Error decoding JSON: ' . json_last_error_msg());
        }
        return $decoded;
    }

    public static function relativePathConverter($relativePath)
    {
        // Convertir ruta relativa en absoluta
        $relativePath = realpath($relativePath);

        // Reemplazar separadores de directorios por barras inclinadas
        $absolutePath = str_replace('\\', '/', $relativePath);

        return $absolutePath;
    }

}
