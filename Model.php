<?php

namespace DgbAuroCore\lib\Inventarium;

use DgbAuroCore\lib\Inventarium\Facade;
use RedBeanPHP\OODBBean;
//TODO ¿Que pasa si no uso una base de datos local y uso una API?
require_once __DIR__ . '/../redbean/rb-mysql.php';

class CustomBeanWrapper extends \RedBeanPHP\OODBBean
{
    protected $bean;

    public function __construct(OODBBean $bean)
    {
        $this->bean = $bean;
    }

    public function &__get($property)
    {

        return $this->bean->$property;
    }
    public function __call($method, $args)
    {
        throw new \BadMethodCallException("El método $method no está implementado en esta clase.");
        return $this->bean->__call($method, $args);
    }

    public function getOriginalBean()
    {
        return $this->bean;
    }
}

use DgbAuroCore\lib\Inventarium\interfaces\SessionManagerInterface;


class Model
{
    protected $env;
    protected $table;
    protected $bean;
    public $created_by = false;
    public $updated_by = false;
    protected $fields = [];
    protected $allowedFields = [];
    private $sessionManager;

    public function __construct(SessionManagerInterface $sessionManager  =  null)

    {
        $this->sessionManager = $sessionManager;

        $this->env = Facade::call('Env');

        function_exists('dgbInTest') ? $this->env::init() : null;

        $env = function ($key = '', $default = null) {
            return $this->env::env($key, $default);
        };

        $dbhost = $env('DBHOST');
        $dbname = $env('DBNAME');
        $dbuser = $env('DBUSER');
        $dbpass = $env('DBPASS');

        if (!\R::testConnection()) {

            \R::setup("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
            \R::addDatabase('db', "mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
            \R::selectDatabase('db');
            \R::exec('SET NAMES utf8mb4');
            \R::exec('SET time_zone = "' . date('P') . '";');
            // \R::freeze(true);
        }

        $this->table = $this->getClassName($env);

        return \R::testConnection();
    }


    public function addFields(array $fields)
    {
        //TODO desde aqui se debería de verificar si los campos estan registrados en el modelo y por ende permitidos? A}ctualmente esa responsabilidad se delega en la insercion o en el update
        $this->fields = $fields;
        //regresar parametro de entrada para usarlo en findOrCreate 
        return $fields;
    }

    function getById($id)
    {
        $bean = \R::load($this->table, $id);
        if ($bean->id) {
            return $bean;
        } else {
            return null;
        }
    }

    public function findOrCreate($condition)
    {

        if (is_array($condition)) {
            //e.g.  $condition = ['name = ? AND id_unico = ?', ['www.unitmodelcest.com', $randomNumber]];
            $bean = \R::findOne($this->table, $condition[0], $condition[1]);
        } else {
            $bean = \R::findOne($this->table, "$condition = ?", [$this->fields[$condition]]);
        }

        if ($bean !== null) {

            if (!is_array($condition)) {
                unset($this->fields[$condition]);
            }

            foreach ($this->fields as $field => $fieldValue) {
                $this->checkAllowedFields($field);
                $bean->$field = $fieldValue;
            }

            $bean = $this->timestamps_create($bean);
            $bean = $this->created_by($bean);

            \R::store($bean);

            return $bean;
        } else {
            return $this->store();
        }
    }



    public function deleteById($id, $fast = false)
    {

        if ($fast) {
            $bean = \R::load($this->table, $id);
            echo function_exists('dgbInTest') ? "usando cache \n" : false;
        } else {

            $bean = \R::loadForUpdate($this->table, $id);
            echo function_exists('dgbInTest') ? "Cache off \n" : false;
        }

        \R::trash($bean);
        return $id;
    }

    public function store()
    {


        \R::ext('xdispense', function ($type) {
            return \R::getRedBean()->dispense($type);
        });

        $bean = \R::xdispense($this->table);

        //TODO estandarizar $var , revisra codigo similar en este doc
        // dgbdc($this->fields);
        foreach ($this->fields as $var => $value) {
            $this->checkAllowedFields($var);
            // dgbdc($value);
            $bean->$var = $value;
        }


        $bean = $this->timestamps_create($bean);
        $bean = $this->created_by($bean);
        $id = \R::store($bean);


        foreach ($this->allowedFields as $key => $value) {
            $name = $value['name']; //Nombre es requerido, si no esta seteado se desea el error
            $unique = isset($value['unique']) ? $value['unique'] : false;
            $type = isset($value['type']) ? $value['type'] : '';
            $attr = isset($value['attr']) ? $value['attr'] : '';
            $indexName = "{$name}_unique";


            if ($unique) {

                // Verificar si la columna existe en la tabla
                $sql = "SHOW COLUMNS FROM {$this->table} LIKE ?";
                $result = \R::getCell($sql, [$name]);

                if (!$result) {
                    // Si la columna no existe, puedes crearla aquí
                    $sql = "ALTER TABLE {$this->table} ADD COLUMN $name $type $attr";

                    \R::exec($sql);
                }


                $sql = "SHOW INDEX FROM {$this->table} WHERE Key_name = ?";
                $result = \R::getRow($sql, [$indexName]);

                if (!$result) {
                    $sql = "ALTER TABLE {$this->table} ADD CONSTRAINT $indexName UNIQUE ($name)";
                    \R::exec($sql);
                }
            }
        }

        return  \R::load($this->table, $id);
    }


    function retrieveRegisterByValue($criterial)
    {

        //e.g retrieveRegisterByValue(['name' => 'id_domain', 'value' => $data['id_domain']]);

        $tableName = $this->table;
        $query = "SHOW TABLES LIKE '$tableName'";

        $tableExists = \R::getCell($query);
        if (!$tableExists) {
            echo "La tabla '$tableName' no existe.\n";
            return null;
        }

        $field = $criterial['name'];
        $value = $criterial['value'];


        $originalBean = \R::findOne($tableName, "$field LIKE ?", [$value]);
        if (!empty($originalBean)) {

            $bean = new CustomBeanWrapper($originalBean);
        }


        if (empty($bean)) {
            echo function_exists('dgbInTest') ? "No hay registros en la tabla '$tableName'. \n" : false;

            return null;
        }


        //Regresar el valor encontrado
        return $bean;
    }



    public function deleteTable()
    {

        $tableName = $this->table;

        $query = "DROP TABLE IF EXISTS $tableName";

        \R::exec($query);

        $query = "SHOW TABLES LIKE '$tableName'";

        $result = \R::getCell($query);

        if ($result === null) {
            echo "La tabla '$tableName' ha sido eliminada correctamente. \n";
        } else {
            echo "No se pudo eliminar la tabla '$tableName'.";
        }
    }

    private function checkAllowedFields($field)
    {

        $isAllowedField = false;
        foreach ($this->allowedFields as $fieldInfo) {
            if ($fieldInfo['name'] === $field) {
                $isAllowedField = true;
                break;
            }
        }
        if (!$isAllowedField ) {
            throw new \Exception('Campo no permitido para este modelo:' . $field);
        }
    }

    private function getClassName($env)
    {

        $output = preg_replace('/(?<!^)([A-Z])/', '_$1', basename(get_called_class()));
        $output = $env('PREFIX') . '_' . strtolower($output);
        return $output;
    }

    private function timestamps_create($bean)
    {

        if (empty($bean['created_at'])) {

            $bean->created_at = date('Y-m-d G:i:s');
            $bean->updated_at = date('Y-m-d G:i:s');
        }

        return $bean;
    }

    private function created_by($bean)
    {

        $user_id = $this->sessionManager->get('user_id');

        if (empty($user_id)) {
            //default id 1 "system" TODO asignarlo desde env, no siempre sera 1 y puede afectar las relaciones entre tablas
            $user_id = 1;
        }

        if (!$this->created_by) {

            $bean->created_by = $user_id;
            $bean->updated_by = $user_id;
            return $bean;
        }


        $bean->created_by = $this->created_by;
        $bean->updated_by = $this->updated_by;

        return $bean;
    }
}
