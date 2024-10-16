<?php
namespace App\Services;

use App\Services\MfServices;

/***
 * Servicio para facilitar el trabajo con los logs,
 * la clase permite de forma simple el manejo de logs en archivos a varios niveles de registro (info,warn,error)
 * @author Eidy Estupiñan Varona <eidy.estupinan@motorflash.com>
 */

/***
 * //Ejemplo de uso
 * $logger = new SimpleLog()   o se obtiene del contenedor de servicios de symfony
 * $logger->settings(true,'my-log',true);
 * $logger->info('Esto es un mensaje de información.');
 * $logger->error('Esto es un mensaje de error.');
 * $logger->addline()
 *
 * $logger->setLogFile(__DIR__.'/my_app.log');
 * $logger->addline()
 * $logger->info('Esto es un mensaje de información.');
 */
class SimpleLog
{
    //ubicación a usar para los archivos de logs   "var/log/"
    private string $location;

    //archivo de log a escribir, ruta completa
    private string $logFile;

    //inlcuir fecha en nombre del archivo (para rotado diario automático de logs)
    private bool $dateOnName;


    public function __construct()
    {
        $this->location = dirname(__DIR__,2).'/var/log/';
        $this->logFile = 'default';
        $this->dateOnName = true;
    }


    /**
     * Función para especificar la configuración del archivo de log.
     *
     * @param bool $defaultLocation Usar la ubicación por defecto?
     *                              Si es true, en el próximo parámetro solo especifica el nombre,
     *                              que usará el archivo, sin la extención ejemplo 'my-archivo', no la ruta completa.
     *                              Si es false, debe pasar la ruta completa al archivo. Ejemplo: '.../directorio/logs/my-archivo.log'
     * @param string $filename Nombre del archivo de log
     * @param bool $dateOnName      Especifica si se le agregará la fecha al nombre del archivo.
     *                              Por defecto, nombre incluye la fecha para que se genere un archivo diario.
     *                              Si no se desea generar un archivo nuevo cada día pasar false
     */
    public function configure(bool $defaultLocation, string $filename, bool $dateOnName = true): void
    {
        if ($defaultLocation)
        {
            $this->location = dirname(__DIR__, 3) . '/var/log/';

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $this->location = dirname(__DIR__, 3) . '\var\log\\';
            }

            if (!file_exists($this->location)) {
                if (!mkdir($this->location, 0777, true) && !is_dir($this->location)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $this->location));
                }
            }

            $this->logFile = $this->location . $filename;
        }
        else {
            $this->logFile = $filename;
        }

        if ($dateOnName) {
            $this->dateOnName = true;
            $date = new \DateTime();

            // Quitar extensión .log si está presente
            if (str_ends_with($this->logFile, '.log')) {
                $this->logFile = substr($this->logFile, 0, -4);
            }
            $this->logFile .= '---' . $date->format('Y-m-d') . '.log';
        }
        else {
            $this->dateOnName = false;

            // Asegurarse de que el archivo tenga extensión .log
            if (!str_ends_with($this->logFile, '.log')) {
                $this->logFile .= '.log';
            }
        }
    }


    /***
     * Obtener la ruta completa al archivo de log
     * @return string
     */
    public function getLogFile(): string
    {
        return $this->logFile;
    }

    /**
     * Establecer el archivo de log
     * @param string $logFile    debeser la ruta completaal archivo
     * @return SimpleLog
     */
    public function setLogFile(string $logFile): SimpleLog
    {
        $this->logFile = $logFile;
        return $this;
    }

    public function log($message, $level = 'INFO', $onlyText=false, $noEoL = false): SimpleLog
    {
        //$timestamp = microtime(true);
        $date = (new \DateTime())->format('Y-m-d H:i:s.u');

        if (!file_exists($this->logFile)) {
            $file = fopen($this->logFile, 'w');
            fclose($file);
        }
        $data ="[$date] [$level] $message";

        if($onlyText)
            $data=$message;

        if(!$noEoL)
            $data.=PHP_EOL;

        file_put_contents($this->logFile, $data, FILE_APPEND);
        return $this;
    }

    public function info($message): SimpleLog
    {
        $this->log($message, 'INFO');
        return $this;
    }

    public function warn($message): SimpleLog
    {
        $this->log($message, 'WARN');
        return $this;
    }


    public function error($message): SimpleLog
    {
        $this->log($message, 'ERROR');
        return $this;
    }

    public function addline(string $charactert='-',$long = 80): SimpleLog
    {
        $this->log(str_repeat($charactert,$long));
        return $this;
    }


}

