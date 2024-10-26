<?php
namespace App\Services\Comun;

/***
 * Servicio para facilitar el trabajo con el sistema de archivos y acceso
 * a funciones complementarias de archivos.
 * @author Eidy Estupiñan Varona <eidy.estupinan@motorflash.com>
 */
class FileManager
{

    //Obtener la ruta completa al directorio de la aplicación
    public function getAppPath(): string
    {
        return dirname(__DIR__, 3) . DIRECTORY_SEPARATOR;
    }



    //Obtener la ruta completa al directorio logs
    public function getAppLogsDirectory(): string
    {
        return $this->getAppPath() . $this->fixPathToOS('var/log/');
    }


    //Obtener la ruta completa a el directorio data
    public function getAppDataDirectory(): string
    {
        return $this->getAppPath() . $this->fixPathToOS('data/');
    }


    //Obtener la ruta completa a el directorio downloads
    public function getDownloadsDirectory(): string
    {
        return $this->getAppPath() . $this->fixPathToOS('data/downloads/');
    }


    //Obtener la ruta completa al directorio de bloqueos
    public function getRunLockDirectory(): string
    {
        return $this->getAppPath() . $this->fixPathToOS('data/run-lock/');
    }


    //Obtener la ruta completa a el directorio publico
    public function getAppPublicDirectory(): string
    {
        return $this->getAppPath() . $this->fixPathToOS('public/');
    }


    //Ajustar una ruta pasada por parámetros para que sea correcta para el sistema operativo
    public function fixPathToOS(string $path): string
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
            return str_replace("/", "\\", $path);
        else
            return str_replace("\\", "/", $path);
    }



    public function copyFile(string $source, string $target): bool
    {
        try {
            if (!file_exists($source)) {
                throw new \RuntimeException("Source file does not exist: $source");
            }
            if (!copy($source, $target)) {
                throw new \RuntimeException("Failed to copy file from $source to $target");
            }
            return true;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }



    public function copyDirectory(string $source, string $target): bool
    {
        try {
            if (!is_dir($source)) {
                throw new \RuntimeException("Source directory does not exist: $source");
            }
            if (!mkdir($target, 0755, true) && !is_dir($target)) {
                throw new \RuntimeException("Failed to create target directory: $target");
            }

            $items = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($items as $item) {
                $targetPath = $target . DIRECTORY_SEPARATOR . $items->getSubPathName();
                if ($item->isDir()) {
                    if (!mkdir($targetPath) && !is_dir($targetPath)) {
                        throw new \RuntimeException("Failed to create directory: $targetPath");
                    }
                } else {
                    if (!copy($item, $targetPath)) {
                        throw new \RuntimeException("Failed to copy file: $targetPath");
                    }
                }
            }

            return true;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }



    public function moveFile(string $source, string $target): bool
    {
        try {
            if (!file_exists($source)) {
                throw new \RuntimeException("Source file does not exist: $source");
            }
            if (!rename($source, $target)) {
                throw new \RuntimeException("Failed to move file from $source to $target");
            }
            return true;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }



    public function moveDirectory(string $source, string $target): bool
    {
        try {
            if (!is_dir($source)) {
                throw new \RuntimeException("Source directory does not exist: $source");
            }
            if (!rename($source, $target)) {
                throw new \RuntimeException("Failed to move directory from $source to $target");
            }
            return true;
        } catch (\Exception $e) {
            // error_log($e->getMessage());
            return false;
        }
    }



    public function deleteFile(string $source): bool
    {
        try {
            if (!file_exists($source)) {
                throw new \RuntimeException("Source file does not exist: $source");
            }
            if (!unlink($source)) {
                throw new \RuntimeException("Failed to delete file: $source");
            }
            return true;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }



    //Eliminar recursivamente un directorio.
    public function deleteDirectoryRecursively(string $directory): bool
    {
        try {
            $items = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($items as $item) {
                if ($item->isDir()) {
                    if (!rmdir($item->getRealPath())) {
                        throw new \RuntimeException(sprintf('Failed to remove directory "%s"', $item->getRealPath()));
                    }
                } else {
                    if (!unlink($item->getRealPath())) {
                        throw new \RuntimeException(sprintf('Failed to remove file "%s"', $item->getRealPath()));
                    }
                }
            }

            if (!rmdir($directory)) {
                throw new \RuntimeException(sprintf('Failed to remove directory "%s"', $directory));
            }

            return true;
        } catch (\Exception $e) {
            // Manejo de la excepción
            // error_log($e->getMessage()); // Loguea el mensaje de error
            return false;
        }
    }



    //Extrar el contenido de un archivo compactado en un directorio especificado.
    public function extractZipFile(string $file, string $outputDirectory, bool $makeDirectory, bool $showProgress = false, $output = null)
    {
        // Verificar si la extensión zip está habilitada
        if (!extension_loaded('zip')) {
            throw new \Exception("La extensión ZIP de PHP no está habilitada.");
        }

        // Verificar que el archivo existe
        if (!file_exists($file)) {
            throw new \Exception("El archivo ZIP no existe: $file");
        }

        // Crear el objeto ZipArchive
        $zip = new \ZipArchive();

        // Intentar abrir el archivo ZIP
        if ($zip->open($file) !== true) {
            throw new \Exception("No se pudo abrir el archivo ZIP: $file");
        }

        // Si se debe crear una carpeta con el nombre del archivo ZIP
        if ($makeDirectory) {
            $nombreArchivoSinExtension = pathinfo($file, PATHINFO_FILENAME);
            $outputDirectory = rtrim($outputDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $nombreArchivoSinExtension;
        }

        // Crear la carpeta de salida si no existe
        if (!is_dir($outputDirectory)) {
            if (!mkdir($outputDirectory, 0777, true) && !is_dir($outputDirectory)) {
                throw new \Exception("No se pudo crear la carpeta de salida: $outputDirectory");
            }
        }

        // Obtener el número total de archivos en el archivo ZIP
        $totalArchivos = $zip->numFiles;
        $extraidos = 0;

        // Extraer el contenido del archivo ZIP
        for ($i = 0; $i < $totalArchivos; $i++) {
            $archivoInfo = $zip->statIndex($i);
            if ($zip->extractTo($outputDirectory, $archivoInfo['name'])) {
                $extraidos++;
                if ($showProgress && !is_null($output)) {
                    $porcentaje = ($extraidos / $totalArchivos) * 100;

                    //$output->write("Progreso: " . round($porcentaje, 2) . "% ($extraidos de $totalArchivos archivos extraídos)");
                    //printf("\rProgreso: %2d%%", ($read / $filesize) * 100);
                    printf("\rProgreso: %2d%%", $porcentaje);
                }
            } else {
                $zip->close();
                throw new \Exception("No se pudo extraer el archivo: " . $archivoInfo['name']);
            }
        }

        // Cerrar el archivo ZIP
        $zip->close();

        // Devolver true si todo salió bien
        return true;
    }


    //compactar un archivo a formato zip usando la extension php-zip
    public function createZipFile(string $file, string $outputFile, bool $showProgress = false, $output = null): void
    {
        //pendiente
    }


    //crear un archivo zip con un directorio como fuente usando la extension php-zip
    public function createZipFileFormDirectory(string $directory, string $outputFile, bool $showProgress = false, $output = null): void
    {
        // pendiente
    }


    public function createDirectory(string $baseDirectory):void
    {
        if (!is_dir($baseDirectory))
        {
            if (!mkdir($baseDirectory, 0777, true) && !is_dir($baseDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $baseDirectory));
            }
        }
    }

}