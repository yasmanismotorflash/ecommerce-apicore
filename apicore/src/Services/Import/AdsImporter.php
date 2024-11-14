<?php
namespace App\Services\Import;


use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Site;
use App\Entity\Advertisement;

use App\Entity\Make;
use App\Entity\Model;
use App\Entity\Version;
use App\Services\Import\Motorflash\APIMF\Transform\AdvertisementBuilder;

class AdsImporter
{
    private EntityManagerInterface $em;
    private Site $site;
    //obtener parámetros del comando
    //Obtener clientes
    //Obtener anuncios
    public function processAdvertisement(EntityManagerInterface $em, Site $site, ?array $ad)
    {
        $this->em = $em;
        $this->site = $site;

        $advertisement = AdvertisementBuilder::getAdvertisement($ad);

        /*// Asociar Dealer
        $dealer = $this->getOrCreateDealer($ad['dealer']);
        $advertisement->setDealer($dealer);

        // Asociar Shop
        $shop = $this->getOrCreateShop($ad['shop']);
        $advertisement->setShop($shop);

        $images = $this->processImages($ad['images']);
        $advertisement->setImages($images);

        $makeName = $advertisement->getMake();
        $modelName = $advertisement->getModel();
        $versionName = $advertisement->getVersion();

        // 1. Obtener o crear la marca (Make)
        $make = $this->entityManager->getRepository(Make::class)->findOneByName($makeName);
        if (!$make) {
            $make = new Make();
            $make->setName($makeName);
            $this->entityManager->persist($make);
            $advertisement->setMakeObject($make);
        }

        // 2. Obtener o crear el modelo y asociarlo a la marca
        $model = $this->entityManager->getRepository(Model::class)->findOneBy(['name' => $modelName, 'make' => $make]);
        if (!$model) {
            $model = new Model();
            $model->setName($modelName);
            $model->setMake($make);
            $this->entityManager->persist($model);
            $advertisement->setModelObject($model);
        }

        // 3. Obtener o crear la versión y asociarla al modelo
        $version = $this->entityManager->getRepository(Version::class)->findOneBy(['name' => $versionName, 'model' => $model]);
        if (!$version) {
            $version = new Version();
            $version->setName($versionName);
            $version->setModel($model);
            $this->entityManager->persist($version);
            $advertisement->setVersionObject($version);
        }

        //$video = VideoBuilder::buildFromString($ad['video']);
        //if($video){
        //    $advertisement->setVideo($video);
        //}
        */
        $this->em->persist($advertisement);
        return $advertisement;
    }
}