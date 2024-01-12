<?php

namespace DgbAuroCore\lib\Inventarium;


class Factory
{

    protected $alias = [];


    public function __construct($alias)
    {
        $this->alias = $alias;
    }


    private function getRealName($name)
    {

        //Podría extraer el alias del $name pero prefiero mantener la mecanica de registrar los alias para restringir errores logicos ya que las librerias deben usar los alias que asigne la app que las consuma

        $aliasName = array_search($name, $this->alias);

        if ($aliasName !== false) {
            return $this->alias[$aliasName];
        }


        if (!isset($this->alias[$name])) {
            throw new \Exception("
            \n\nFactory-build Err: Alias for class '$name' not found.
            \nRegister it using addAlias method or use Facade::call instead.
            ");
        }

        return $this->alias[$name];
    }


    public function build($name, $params = [])
    {


        try {

            $name = $this->getRealName($name);

            $reflection = new \ReflectionClass($name);

            // Verifica si la clase es instanciable
            if (!$reflection->isInstantiable()) {
                throw new \Exception($name . " is not instantiable");
            }

            // Obtiene el constructor de la clase
            $constructor = $reflection->getConstructor();

            // Si no hay constructor, devuelve una nueva instancia de la clase
            if (is_null($constructor)) {
                return new $name;
            }

            // Obtiene los parámetros del constructor
            $parameters = $constructor->getParameters();


            $args = [];

            // Recorre los parámetros del constructor
            $i = 0;
            foreach ($parameters as $parameter) {
                $param_name = $parameter->getName();

                if (array_key_exists($param_name, $params)) {
                    // Utiliza el valor proporcionado en los parámetros por nombre
                    $args[] = $params[$param_name];
                } elseif ($parameter->getClass() !== null) {
                    // El parámetro es de tipo clase
                    $class_name = $parameter->getClass()->getName();
                    $args[] = $this->build($class_name, []);
                } else {
                    // Construye la instancia del parámetro recursivamente
                    $value = null;
                    if ($parameter->isDefaultValueAvailable() && !isset($params[$i])) {
                        $value = $parameter->getDefaultValue();
                    } elseif (isset($params[$i])) {
                        $value = $params[$i];
                    }


                    //Son necesarios los parametros y no se enviaron al instanciar la clase
                    if (is_null($value)) {
                        // dgbdd($parameter->isDefaultValueAvailable());
                        // dgbdd($parameter);
                        // dgbdd($parameter->getDefaultValue());
                        // dgbdd($parameters);
                        $errorMessage = "Missing parameter at index $i on class $name";
                        throw new \Exception($errorMessage);
                    }

                    $args[] = $value;
                }
                $i++;
                // Crea una nueva instancia de la clase con los argumentos construidos

            }

            if (is_subclass_of($name, Singleton::class)) {
                return $name::create($args);
            }


            return $reflection->newInstanceArgs($args);
        } catch (\Exception $e) {

            if (strpos($name, ".php") !== false) {
                throw new \Exception('Factory->bulid Err: No debes incluir la extension php en el namespace ');
            }



            $msg = 'Error construyendo "' . $name . '": ' . $e->getMessage() . ' linea: ' . $e->getLine();

            //TODO muy largo y util, meter en util.php o algun lado refactorizar
            $filteredTrace = '';
            foreach ($e->getTrace() as $trace) {
                if (isset($trace['class']) && strpos($trace['class'], 'Codeception\\') === 0) {
                    continue; // Omitir llamadas de Codeception
                }

                $filteredTrace .= "\n";
                $filteredTrace .= isset($trace['file']) ? $trace['file'] . ':' : '';
                $filteredTrace .= isset($trace['line']) ? $trace['line'] . ' ' : '';
                $filteredTrace .= isset($trace['class']) ? $trace['class'] . '->' : '';
                $filteredTrace .= isset($trace['function']) ? $trace['function'] : '';
            }

            $msg .= "\nStack trace:\n" . $filteredTrace;

            throw new \Exception($msg);
        }
    }

    public function addAlias(string $alias, string $realName)
    {
        $this->alias[$alias] =  $realName;
    }
}
