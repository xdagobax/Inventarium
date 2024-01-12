<?php

namespace DgbAuroCore\lib\Inventarium\tests\mockups;

class conexion
{

    private $conexion;
    public function __construct()
    {

        $host = 'localhost';
        $user = '-dgbDeveloper33#';
        $password = '-dgbDeveloper33#pass';
        $database = 'wcadmin';

        $this->conexion = mysqli_connect($host, $user, $password, $database);

        // Verificar si la conexión fue exitosa
        if (mysqli_connect_errno()) {
            echo 'Error al conectar con la base de datos: ' . mysqli_connect_error();
            exit;
        }
    }
    public function info()
    {

        // Obtener el nombre del motor de base de datos
        $nombre = mysqli_get_server_info($this->conexion);

        // Mostrar el nombre del motor de base de datos
        echo 'Version del motor de base de datos: ' . $nombre;

        $nombreHost = mysqli_get_host_info($this->conexion);
        echo '<br>Nombre del motor de base de datos: ' . $nombreHost;

        // Ejecutar una consulta para obtener el nombre del motor de base de datos
        $resultado = mysqli_query($this->conexion, 'SELECT @@version');

        $fila = mysqli_fetch_assoc($resultado);
        $nombreMotor = $fila['@@version'];

        var_dump($fila);
        echo '<br>Motor de base de datos: ' . $nombreMotor;

        // Obtener información detallada del servidor
        $infoServidor = mysqli_get_server_info($this->conexion);

        // Extraer el nombre del motor de base de datos ...
        $nombreMotor = '';

        if (strpos($infoServidor, 'MariaDB') !== false) {
            $nombreMotor = 'MariaDB';
        } elseif (strpos($infoServidor, 'MySQL') !== false) {
            $nombreMotor = 'MySQL';
        }

        echo '<br>Motor de base de datos: ' . $nombreMotor;


        // Obtener el nombre y directorio del motor de base de datos
        $nombreMotor = mysqli_get_client_info();
        $directorioMotor = mysqli_get_client_info($this->conexion);

        echo '<br>Motor de base de datos: ' . $nombreMotor . PHP_EOL;
        echo 'Directorio del motor de base de datos: ' . $directorioMotor;

        mysqli_close($this->conexion);
    }

    public function testCTEs()
    {

        // Consulta con una CTE
        $sql = "WITH cte AS (SELECT * FROM wp_users) SELECT * FROM cte";

        if ($this->conexion->query($sql) === true) {
            return "La base de datos soporta CTEs";
        } else {
            return "La base de datos no soporta CTEs: " . $this->conexion->error;
        }
    }
}
