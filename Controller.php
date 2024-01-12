<?php

namespace DgbAuroCore\lib\Inventarium;

use DgbAuroCore\lib\Inventarium\Render;

class Controller

{

    protected $inputData;

    function __construct()
    {
    }

    public function apiFn($type, $fn, $requireData = true)
    {
        $data = null;
        
        if (!empty(file_get_contents("php://input"))) {

            $data = file_get_contents("php://input");
            $data = $this->dgb_decript($data);
            // dgbdd($data);
            $data = Facade::call('Util')->dgbJsonDecode($data, true);
        }

        if ($requireData && is_null($data)) {
            http_response_code(403);
            return 403;
        }

        $fn = $type . '_' . $fn;

        return $this->$fn($data);
    }
    private function dgb_decript($val)
    {
        //  echo $val .'-----------------------------';
        // Clave privada (omítela aquí)
        $key = '-----BEGIN PRIVATE KEY----- MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDRcuUvidx1V97s 0VweYXyiQWHvqtKzsTbbUL15siYIT1FjQfrFOQNrmpwRpe/Gh1uiDsKlkiQNtdDu XY1ZgSXx/6L7oOs9GEONJ4U95jjupcya/B3RDeFhWqBiyi2toD3Y2romtM6JWQh8 cewuiBfu1n8qJd5Qjn4s/PyQz83qG0DB6xaJu8lqZonvRDVOWffQ1g4ZRlUtuDhU 3XoaC6ilPLhDUrjAxAGsWhD7PO9fpwHz8zxoZRiFJWmis+KN/qXxU/3pmFjYsr6s f5v36qvW+pulu4ytIB6YdWavsOhVCg6kCmp0r0ZkMAE2DihimeDi69CaST7r3CT7 O4sOwQjvAgMBAAECggEAStW6fZnMY0DaLdNADrrWRUMHMxLd5cZTy1i12l2EqUNt 2QEiEIWmazP6v3OCkHSckPKlKXNl3mAwHnrenjQ+0pB8M1vzsQiSWkTcnpk7jkk/ KcEbBi7ncFlpJOMIHxk5/9lm2Z2QjhJ5rb0mnhmSM4wAM5cEL9Q50OkzdKpK8HHW 299jAZUaglPxJmi/8mQlCoinqS//+9p3XmUEjJalpc0wRDl2EuU0DbVA2fuFcn8i +4CEMH2pb2XFQUX2tEgCCzR+KICJbg6/iklRVwWNFKLWaQb/oJHhK42vZmQpa/cN LaG3RVwQxL91CyndP51ecVavdAAIZRqm19/fNAHIgQKBgQDrJiMcnacJaLxMk4gZ rYblYtxij7+9C0XO7pv7UonUe6achz//dqF8v576BsdAbKX+RH9awjifT3BQZtgC oRkJd9DrILwBfJjgdeZNCxlDquYUtoq3/ACJEiM14Zg1ucewbgBcZxSMDuJl8Ho9 8Qd/GSqoVWm1KesVObMFIJMi6QKBgQDkBV3PZ8AhRR1jd0Ow+m2JCrtJNdQo7cSt hcgQ/1wyIo71Jg7DTM9xNsvl5bfl5ZJ9k/OUJawldCnld49YmQkNLW+6vmEsmz8Q wTdPy8UHrZyVf4fZEdbibnThbs/YIEh6UpBXnlEbDotVthC4DLMziPoWDDQnIdqK b9u1jfX2FwKBgQDV5aT+vLFar7jI91JP+TbBpcHOre+9qGlhqE6euB/D9o5ByA2A 9YpDjZKw4EWS5sG/ntMx3YXf9ES3/jTzf1Lk9+ZEW0xpFcuCMfzBjBz5HpWXtcxN O5/JD4ST2Je6Fzn71LPjWaipFkj+bVbkzUECbgQNh1RWhMDgV9HuEgmFMQKBgBVr G6C70iRN5bDOJyGcpGVXCLibwc4GZ48VHfHaysI61MraRFC8ClBMIWL67Qc/RT9e gmbMExuBid3wGRrtZRhDyFb2QxJ5krb0L0njjiJmgGrai9wr/M9QKNJ0Ijne7m/v CXfxexTbjOMEMxXWu45/s14Wz19NosWWNEEdkJkJAoGAPnfBSfa9k86tin7ly69k UeFRAjazCZD9IkMU6jvpKmhbk9XS1URj0jXua2YQZLR+Eign3rpgQ8NjpM6VgWHH osuLZtiCzaDvrF3ivx//C7xCfXmpncp0oOQ0cIS+StXqWYhnGUorn+VP4Oc3M4IU wHARmKK0R8oKbIhvINdDKj0= -----END PRIVATE KEY-----';

        // Eliminar espacios en blanco adicionales y caracteres no deseados de la clave privada
        $key = str_replace(['-----BEGIN PRIVATE KEY-----', '-----END PRIVATE KEY-----', "\n", "\r"], '', $key);
        $key = "-----BEGIN PRIVATE KEY-----\n" . wordwrap($key, 64, "\n", true) . "\n-----END PRIVATE KEY-----";

        // Descifrar el valor cifrado con la clave privada
        openssl_private_decrypt(base64_decode($val), $valorDescifrado, $key);

        // Imprimir el valor descifrado
        return $valorDescifrado;
    }

    protected function render($fileAlias, $data = array(), $showEmptys = false)
    {
        return Render::RenderData($fileAlias, $data, $showEmptys);
    }

}
