<?php

namespace ConsoleComponent\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;


class DeployPluginCommand extends Command
{

    protected function configure()
    {

        $this->setName('dagoba:DeployPlugin')
            ->setDescription('Zip del plugin y copia al entorno de producción local')
            ->addArgument('folder', InputArgument::REQUIRED, 'Folder del plugin en desarrollo', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $folderName = $input->getArgument('folder');
        $root = 'C:/laragon/www';
        $folder = 'C:/laragon/www/apps/' . $folderName;
        $destiny = "C:/laragon/www/dist";

        // Eliminar el archivo ZIP si existe
        $existingZipFile = "$destiny/$folderName.zip";
        if (file_exists($existingZipFile)) {
            unlink($existingZipFile);
        }

        // Eliminar la carpeta de destino si existe
        $existingDestinyFolder = "$destiny/$folderName";
        if (is_dir($existingDestinyFolder)) {
            $this->deleteDirectory($existingDestinyFolder);
        }



        // Leer la configuración desde el archivo dist.config
        $configFile = "$folder/dist.config";
        $config = parse_ini_file($configFile, true);

        if ($config === false) {
            die("Error al leer el archivo de configuración: $configFile");
        }

        $zip = new \ZipArchive();

        if ($zip->open("$destiny/$folderName.zip", \ZipArchive::CREATE) === false) {
            die('Error al abrir el archivo ZIP');
        }

        // Agregar archivos del folder del parámetro
        if (is_dir($folder) === true) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder), \RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file) {
                $file = str_replace('\\', '/', $file);

                // Evitar agregar el archivo ZIP a sí mismo
                if (strpos($file, "$folderName.zip") !== false) {
                    continue;
                }

                // Evitar agregar la carpeta .git y sus contenidos
                if (strpos($file, '.git') !== false && strpos($file, '.gitkeep') === false) {
                    continue;
                }
                // Evitar agregar la carpeta .git y sus contenidos
                if (
                    strpos($file, 'tests') !== false
                    || strpos($file, '.sql') !== false
                    || strpos($file, '.log') !== false
                    || strpos($file, '.zip') !== false

                ) {
                    continue;
                }

                // Utilizar el nombre del parámetro folder como el directorio base en el ZIP
                $localPath = $folderName . '/' . str_replace($folder . '/', '', $file);

                if (is_dir($file) === true) {
                    // No hacer nada para las carpetas, ya que serán creadas automáticamente durante la extracción
                } else if (is_file($file) === true) {
                    $zip->addFile($file, $localPath);
                }
            }
        }

        // Agregar dependencias según la configuración
        foreach ($config['dependencies'] as $dependencyName => $dependencyPath) {
            $configDependecyPath = $dependencyPath;
            $dependencyPath = $root . '/' . $dependencyPath;

            if (is_dir($dependencyPath) === true) {
                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dependencyPath), \RecursiveIteratorIterator::SELF_FIRST);

                foreach ($files as $file) {
                    $file = str_replace('\\', '/', $file);

                    // Evitar agregar el archivo ZIP a sí mismo
                    if (strpos($file, "$folderName.zip") !== false) {
                        continue;
                    }

                    // Evitar agregar la carpeta .git y sus contenidos
                    if (strpos($file, '.git') !== false && strpos($file, '.gitkeep') === false) {
                        continue;
                    }
                    // Evitar agregar la carpeta .git y sus contenidos
                    if (
                        strpos($file, 'tests') !== false
                        || strpos($file, '.sql') !== false
                        || strpos($file, '.log') !== false
                        || strpos($file, '.zip') !== false

                    ) {
                        continue;
                    }


                    if (
                        strpos($file, 'Inventarium/vendor') !== false
                        || strpos($file, 'Inventarium/app') !== false
                        || strpos($file, 'Inventarium/app.php') !== false

                    ) {
                        continue;
                    }



                    $localPath = $folderName . '/vendor/' . $configDependecyPath . '/' . str_replace($dependencyPath . '/', '', $file);



                    if (is_dir($file) === true) {
                        // No hacer nada para las carpetas, ya que serán creadas automáticamente durante la extracción
                    } else if (is_file($file) === true) {
                        $zip->addFile($file, $localPath);
                    }
                }
            }
        }

        $zip->close();

        // Extraer el ZIP en la ubicación de destino
        $zip = new \ZipArchive();

        if ($zip->open("$destiny/$folderName.zip") === true) {
            // Asegurar que el directorio de destino exista
            if (!is_dir("$destiny")) {
                mkdir("$destiny");
            }

            // Extraer el contenido del ZIP en la carpeta de destino
            $zip->extractTo("$destiny");
            $zip->close();
        } else {
            die('Error al abrir el archivo ZIP para extraer');
        }

        $output->writeln('Elegiste: ' . $folderName);
        return 0;
    }

    // Función para eliminar una carpeta y su contenido de forma recursiva
    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }


    protected function interact(InputInterface $input, OutputInterface $output)
    {


        $plugin = $input->getArgument('folder');

        if (is_dir("C:/laragon/www/apps/$plugin") === false) {
            $output->writeln("No existe el plugin ¿Lo escribiste bien?");
            die;
        }
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {

        echo "Iniciando \n\r";
    }

    // private function validateDate($date){

    //     $d = \DateTime::createFromFormat('Y-m-d',$date);
    //     return $d && $d->format('Y-m-d') == $date;

    // }




}
