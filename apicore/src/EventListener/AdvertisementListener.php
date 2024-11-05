<?php
namespace App\EventListener;

use App\Entity\Advertisement;
use App\Entity\Make;
use App\Entity\Model;
use App\Entity\Version;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;


class AdvertisementListener
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function prePersist($args)
    {
        $advertisement = $args->getObject();

        if (!($advertisement instanceof Advertisement)) {
            return; // Ignora si no es el tipo correcto
        }

        $makeName = $advertisement->getMake();
        $modelName = $advertisement->getModel();
        $versionName = $advertisement->getVersion();

        // 1. Obtener o crear la marca (Make)
        $make = $this->entityManager->getRepository(Make::class)->findOneByName($makeName);
        if (!$make) {
            $make = new Make();
            $make->setName($makeName);
            $this->entityManager->persist($make);
        }

        // 2. Obtener o crear el modelo y asociarlo a la marca
        $model = $this->entityManager->getRepository(Model::class)->findOneBy(['name' => $modelName, 'make' => $make]);
        if (!$model) {
            $model = new Model();
            $model->setName($modelName);
            $model->setMake($make);
            $this->entityManager->persist($model);
        }

        // 3. Obtener o crear la versión y asociarla al modelo
        $version = $this->entityManager->getRepository(Version::class)->findOneBy(['name' => $versionName, 'model' => $model]);
        if (!$version) {
            $version = new Version();
            $version->setName($versionName);
            $version->setModel($model);
            $this->entityManager->persist($version);
        }
    }
}