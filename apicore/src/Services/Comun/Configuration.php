<?php
namespace App\Services\Comun;

use App\Entity\ConfigValue;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\Comun\SimpleLog;

/***
 * Servicio para gestionar parámetros de configuración almacenados en base de datos
 * para facilitar el cambio y la gestión de los mismos desde la aplicación.
 * @author Eidy Estupiñan Varona <eidy.estupinan@motorflash.com>
 */
class Configuration
{
    private SimpleLog $log;
    private EntityManagerInterface $entityManager;

    private bool $debug = true;


    public function __construct(SimpleLog $log,EntityManagerInterface $entityManager)
    {
        $this->log = $log;
        $this->entityManager = $entityManager;
    }


    public function configure(string $logname = 'config-service'):Configuration
    {
        $this->debug = boolval($_ENV['APP_DEBUG']);
        if ($this->debug)
            $this->log->configure(true,$logname,true);
        return $this;
    }

    //Crea un parámetro de configuración o lo actualiza si su tipo o valor cambian,
    //lo guarda en la base de datos
    public function setParameter(string $parameterName, string $parameterType, string $parameterValue):Configuration
    {
        $parameter = $this->getParameter($parameterName);
        if(!$parameter)
        {
            $parametro = new ConfigValue();
            $parametro->initialize($parameterName,$parameterType,(string)$parameterValue);
            $this->entityManager->persist($parametro);
            $this->entityManager->flush();
            if ($this->debug)
                $this->log->info('Creando nuevo parámetro: [nombre:' . $parameterName.', tipo:'.$parameterType.', valor:'.$parameterValue.']');
        }
        else
        {
            if($parameter->getType()!==$parameterType || $parameter->getValueStr()!==$parameterValue)
                $this->updateParameter($parameterName, $parameterType, $parameterValue);
        }
        return $this;
    }


    //Obtiene un parametro de configuracion almacenado especificando su nombre
    public function getParameter(string $parameterName):?ConfigValue
    {
        $repositorio = $this->entityManager->getRepository(ConfigValue::class);
        $parameter = $repositorio->findOneByName($parameterName);

        if ($this->debug)
            $this->log->info('Pedido parámetro: [nombre: '.$parameterName.'], Encontrado :'.(($parameter)?'si':'no').', Valor:'.(($parameter)?$parameter->getValueStr():'nulo').']');

        if(!$parameter)
            return null;
        return $parameter;
    }


    //Actualiza un parametro de configuracion
    public function updateParameter(string $parameterName, string $parameterType, $parameterValue):Configuration
    {
        $parameter = $this->getParameter($parameterName);
        if($parameter)
        {
            $updated = false;
            if($parameter->getType()!== $parameterType)
            {
                $parameter->setType($parameterType);
                $updated = true;
            }
            if($parameter->getValueStr()!== $parameterValue )
            {
                $parameter->setValueStr((string)$parameterValue);
                $updated = true;
            }
            if($updated)
            {
                $this->entityManager->flush();
                if ($this->debug)
                    $this->log->info('Actualizado parámetro: [nombre:' . $parameterName.', tipo:'.$parameterType.', valor:'.$parameterValue.']');
            }
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }


    /**
     * @param bool $debug
     * @return Configuration
     */
    public function setDebug(bool $debug): Configuration
    {
        $this->debug = $debug;
        return $this;
    }



}