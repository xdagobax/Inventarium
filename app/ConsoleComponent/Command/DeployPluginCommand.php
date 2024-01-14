<?php

namespace ConsoleComponent\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

//TODO ¿Cambiar e nombre al archivo ?  Ahora se trata de hacer deploy de apps y plugins

class DeployPluginCommand extends Command
{

    protected function configure()
    {

        $this->setName('dagoba:DeployPlugin')
            ->setDescription('Zip del plugin y copia al entorno de producción local')
            ->addArgument('folder', InputArgument::REQUIRED, 'Folder del plugin en desarrollo', null)
            ->addArgument('version', InputArgument::REQUIRED, 'Version del plugin en desarrollo', null)
            ->addArgument('deleteZip', InputArgument::OPTIONAL, 'Eliminar el zip al finalizar', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $folderName = $input->getArgument('folder');
        $version = $input->getArgument('version');
        $deleteZipOption = $input->getArgument('deleteZip');
        $root = 'C:/laragon/www';
        $folder = 'C:/laragon/www/apps/' . $folderName;
        $destiny = "C:/laragon/www/dist";
        $path = "$destiny/$folderName";


        $this->deletePreviousContent($path);

        //TODO ¿que pasa si no hay dependencias o configuraciones? El nombre $config expresa lo que es?
        $config = $this->checkConfigFile($folder);

        $zip = $this->createZip($path);

        $zip = $this->addAppFilesToZip($folder, $folderName, $zip,$version);

        $zip = $this->addDependencyFilesToZip($root, $config, $folderName, $zip);

        $zip->close();

        $this->extractZipFiles($path, $destiny);

        // $this->deleteZip($path); //XXX hay que implementarlo como opcional


        $output->writeln('Elegiste: ' . $folderName);
        $output->writeln('Velo en : http://localhost/dist/' . $folderName);
        return 0;
    }


    private function addAppFilesToZip($folder, $folderName, $zip,$version)
    {

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
                    || strpos($file, '40') !== false
                    || strpos($file, '.sql') !== false
                    || strpos($file, '.log') !== false
                    || strpos($file, '.zip') !== false
                    || strpos($file, "$folderName/dist") !== false

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

        $versionFile = $this->createVersioFile($folder,$version);
        $zip->addFile($versionFile, "$folderName/app/version.txt");


        return $zip;
    }

    private function createVersioFile($folder, $version)
    {

        $versionFile = "$folder/dist/version.txt";

        $maxAttempts = 5;
        $attempts = 0;

        do {
            file_put_contents($versionFile, $version);
            sleep(1);  // Puedes ajustar la pausa según tus necesidades
            $attempts++;
        } while (!file_exists($versionFile) && $attempts < $maxAttempts );

        // while ($attempts < $maxAttempts) {
        //     if (!file_exists($versionFile)) {
        //         file_put_contents($versionFile, $version);
        //         break;  // Salir del bucle una vez que se haya creado el versionFile
        //     } else {
        //         // El versionFile ya existe, esperar antes de volver a intentar
        //         $attempts++;
        //         sleep(2);  // Puedes ajustar la pausa según tus necesidades
        //     }
        // }

        if ($attempts === $maxAttempts) {
            echo "No se pudo crear el versionFile después de $maxAttempts intentos.";
        }
        return $versionFile;
    }

    private function checkConfigFile($folder)
    {
        // Leer la configuración desde el archivo dist.config
        $configFile = "$folder/dist/dist.config";
        if (!file_exists($configFile)) {
            die('El archivo dist.confog no existe');
        }
        $config = parse_ini_file($configFile, true);

        if ($config === false) {
            die("Error al leer el archivo de configuración: $configFile");
        }
        return $config;
    }

    private function addDependencyFilesToZip($root, $config, $folderName, $zip)
    {

        //TODO no necesariamente las dependencias y el destino estaran en la misma carpeta
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
                        || strpos($file, '40') !== false
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


                    $localPath = $folderName . '/vendor/' . $dependencyName . '/' . str_replace($dependencyPath . '/', '', $file);

                    if (is_dir($file) === true) {
                        // No hacer nada para las carpetas, ya que serán creadas automáticamente durante la extracción
                    } else if (is_file($file) === true) {
                        $zip->addFile($file, $localPath);
                    }
                }
            }
        }
        return $zip;
    }

    private function extractZipFiles($path, $destiny)
    {
        // Extraer el ZIP en la ubicación de destino
        $zip = new \ZipArchive();

        if ($zip->open("$path.zip") === true) {
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
    }

    private function deleteZip($path)
    {

        // Eliminar el archivo ZIP después de descomprimirlo
        if (file_exists("$path.zip")) {
            unlink("$path.zip");
        } else {
            die('Error: El archivo ZIP no existe');
        }
    }

    private function createZip($path)
    {
        $zip = new \ZipArchive();

        if ($zip->open("$path.zip", \ZipArchive::CREATE) === false) {
            die('Error al abrir el archivo ZIP');
        }
        return $zip;
    }



    private function deletePreviousContent($path)
    {
        // Eliminar el archivo ZIP si existe
        $existingZipFile = "$path.zip";
        if (file_exists($existingZipFile)) {
            unlink($existingZipFile);
        }

        // Eliminar la carpeta de destino si existe
        $existingDestinyFolder = "$path";
        if (is_dir($existingDestinyFolder)) {
            $this->deleteDirectory($existingDestinyFolder);
        }
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
            $output->writeln("No existe el folder $plugin ¿Lo escribiste bien?");
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
