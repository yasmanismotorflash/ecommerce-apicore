<?php
namespace App\Services\Import\Motorflash\APIMF\Transform;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Site;
use App\Entity\Advertisement;
use App\Entity\Make;
use App\Entity\Model;
use App\Entity\Version;
use App\Entity\Dealer;
use App\Entity\Shop;

class AdvertisementBuilder
{
    /**
     * Obtener anuncio a partir de datos pasados en arreglo
 * @param EntityManagerInterface $em
     * @param Site $site
     * @param array|null $ad
     * @return Advertisement|null devuelve un objeto Advertisement o null según los siguientes casos.
     *  - sino existe se creará uno nuevo a partir de los datos pasados,
     *  - si existe se actualiza con los datos pasados,
     *  - si el arreglo proporcionado no es válido devuelve null
     */
    static function getAdvertisement(EntityManagerInterface $em, Site $site, ?array $ad): ?Advertisement {
        if(isset($ad['id'])) {
            $adverteisement = $em->getRepository(Advertisement::class)->findOneByMfid($ad['id']);
            if($adverteisement){
                return AdvertisementBuilder::updateAdvertisement($em, $site, $adverteisement, $ad);
            }
            return AdvertisementBuilder::createAdvertisement($em, $site, $ad);
        }
        return null;
    }


    static function updateAdvertisement(EntityManagerInterface $em, Site $site, Advertisement $advertisement, ?array $data): ?Advertisement {
        if(AdvertisementBuilder::validateArray($data)) {
            // ToDo: Actualizar entidad con todos los datos disponibles en el arreglo y llenar el registro de cmabios

            $advertisement->addSite($site);

            if(isset($data['id'])) { $advertisement->setMfid($data['id']);}
            if(isset($data['published'])) { $advertisement->setPublished($data['published']);}
            if(isset($data['daysPublished'])) { $advertisement->setDaysPublished($data['daysPublished']);}
            if(isset($data['available'])) { $advertisement->setAvailable($data['available']);}

            $dealer = DealerBuilder::getDealer($em,$site, $data['dealer']);
            $advertisement->setDealer($dealer);

            $shop = ShopBuilder::getShop($em,$site, $dealer, $data['shop']);
            $advertisement->setShop($shop);

            if(isset($data['name'])) { $advertisement->setName($data['name']);}

            $make = MakeBuilder::getMake($em,$site, $data['make']);
            $advertisement->setMake($make);

            $model = ModelBuilder::getModel($em,$site, $make, $data['model']);
            $advertisement->setModel($model);

            $version = VersionBuilder::getVersion($em, $site, $model, $data['version']);
            $advertisement->setVersion($version);

            $finish = FinishBuilder::getFinish($em, $site, $model, $data['finish']);
            $advertisement->setFinish($finish);

            $images = ImageBuilder::loadImages($em, $data['images']);
            $advertisement->setImages($images);

            $video = VideoBuilder::createVideo($em, $data['video']);
            $advertisement->setVideo($video);


            if(isset($data['vin'])) { $advertisement->setVin($data['vin']);}
            if(isset($data['plate'])) { $advertisement->setPlate($data['plate']);}
            if(isset($data['registrationDate'])) { $advertisement->setRegistrationDate(\DateTime::createFromFormat('Y-m-d', $data['registrationDate']));}

            // ToDo: faltan algunos campos fecha

            if(isset($data['jato'])) { $advertisement->setJato($data['jato']);}
            if(isset($data['typnatcode'])) { $advertisement->setTypnatcode($data['typnatcode']);}
            if(isset($data['internalRef'])) { $advertisement->setInternalRef($data['internalRef']);}
            if(isset($data['origen'])) { $advertisement->setOrigen($data['origen']);}


            //if(isset($data['images'])) { $entity->setImages($data['images']);}

            if(isset($data['status'])) { $advertisement->setStatus($data['status']);}
            if(isset($data['typeVehicle'])) { $advertisement->setTypeVehicle($data['typeVehicle']);}
            if(isset($data['bodyType'])) { $advertisement->setBodyType($data['bodyType']);}
            if(isset($data['bodyTypeEs'])) { $advertisement->setBodyTypeEs($data['bodyTypeEs']);}
            if(isset($data['iva'])) { $advertisement->setIva($data['iva']);}

            if(isset($data['price'])) { $advertisement->setPrice((float)$data['price']);}
            if(isset($data['financedPrice'])) { $advertisement->setFinancedPrice((float)$data['financedPrice']);}
            if(isset($data['purchasePrice'])) { $advertisement->setPurchasePrice((float)$data['purchasePrice']);}
            if(isset($data['priceNew'])) { $advertisement->setPriceNew((float)$data['priceNew']);}

            if(isset($data['km'])) { $advertisement->setKm((int)$data['km']);}
            if(isset($data['cv'])) { $advertisement->setCv((int)$data['cv']);}
            if(isset($data['kw'])) { $advertisement->setKw((int)$data['kw']);}
            if(isset($data['cc'])) { $advertisement->setCc((int)$data['cc']);}

            if(isset($data['tires_front'])) { $advertisement->setTiresFront($data['tires_front']);}
            if(isset($data['tires_back'])) { $advertisement->setTiresBack($data['tires_back']);}
            if(isset($data['color'])) { $advertisement->setColor($data['color']);}
            if(isset($data['freeAccidents'])) { $advertisement->setFreeAccidents($data['freeAccidents']);}
            if(isset($data['gearbox'])) { $advertisement->setGearbox($data['gearbox']);}
            if(isset($data['number_of_gears'])) { $advertisement->setNumberOfGears(intval($data['number_of_gears']));}
            if(isset($data['doors'])) { $advertisement->setDoors(intval($data['doors']));}
            if(isset($data['seats'])) { $advertisement->setSeats(intval($data['seats']));}
            if(isset($data['environmentalBadge'])) { $advertisement->setEnvironmentalBadge($data['environmentalBadge']);}
            if(isset($data['warrantyDuration'])) { $advertisement->setWarrantyDuration(intval($data['warrantyDuration']));}

            //  ToDo: Validar si el anuncio está en el sitio especificado, si no está agregarlo

            return $advertisement;
        }
        // ToDo: Mostrar error en pantalla y log , pasar el output
        return null;
    }



    static function createAdvertisement(EntityManagerInterface $em, Site $site, ?array $data): ?Advertisement {
         if(AdvertisementBuilder::validateArray($data)) {
             // ToDo: Crear entidad con todos los datos disponibles en el arreglo

             $advertisement = new Advertisement();

             $advertisement->addSite($site);

             if(isset($data['id'])) { $advertisement->setMfid($data['id']);}
             if(isset($data['published'])) { $advertisement->setPublished($data['published']);}
             if(isset($data['daysPublished'])) { $advertisement->setDaysPublished($data['daysPublished']);}
             if(isset($data['available'])) { $advertisement->setAvailable($data['available']);}

             $dealer = DealerBuilder::getDealer($em,$site, $data['dealer']);
             $advertisement->setDealer($dealer);

             $shop = ShopBuilder::getShop($em,$site, $dealer, $data['shop']);
             $advertisement->setShop($shop);

             if(isset($data['name'])) { $advertisement->setName($data['name']);}

             $make = MakeBuilder::getMake($em,$site, $data['make']);
             $advertisement->setMake($make);

             $model = ModelBuilder::getModel($em,$site, $make, $data['model']);
             $advertisement->setModel($model);

             $version = VersionBuilder::getVersion($em, $site, $model, $data['version']);
             $advertisement->setVersion($version);

             $finish = FinishBuilder::getFinish($em, $site, $model, $data['finish']);
             $advertisement->setFinish($finish);

             $images = ImageBuilder::loadImages($em, $data['images']);
             $advertisement->setImages($images);


             if(isset($data['vin'])) { $advertisement->setVin($data['vin']);}
             if(isset($data['plate'])) { $advertisement->setPlate($data['plate']);}
             if(isset($data['registrationDate'])) { $advertisement->setRegistrationDate(\DateTime::createFromFormat('Y-m-d', $data['registrationDate']));}

             // ToDo: faltan algunos campos fecha


             if(isset($data['jato'])) { $advertisement->setJato($data['jato']);}
             if(isset($data['typnatcode'])) { $advertisement->setTypnatcode($data['typnatcode']);}
             if(isset($data['internalRef'])) { $advertisement->setInternalRef($data['internalRef']);}
             if(isset($data['origen'])) { $advertisement->setOrigen($data['origen']);}




             if(isset($data['status'])) { $advertisement->setStatus($data['status']);}
             if(isset($data['typeVehicle'])) { $advertisement->setTypeVehicle($data['typeVehicle']);}
             if(isset($data['bodyType'])) { $advertisement->setBodyType($data['bodyType']);}
             if(isset($data['bodyTypeEs'])) { $advertisement->setBodyTypeEs($data['bodyTypeEs']);}
             if(isset($data['iva'])) { $advertisement->setIva($data['iva']);}

             if(isset($data['price'])) { $advertisement->setPrice((float)$data['price']);}
             if(isset($data['financedPrice'])) { $advertisement->setFinancedPrice((float)$data['financedPrice']);}
             if(isset($data['purchasePrice'])) { $advertisement->setPurchasePrice((float)$data['purchasePrice']);}
             if(isset($data['priceNew'])) { $advertisement->setPriceNew((float)$data['priceNew']);}

             if(isset($data['km'])) { $advertisement->setKm((int)$data['km']);}
             if(isset($data['cv'])) { $advertisement->setCv((int)$data['cv']);}
             if(isset($data['kw'])) { $advertisement->setKw((int)$data['kw']);}
             if(isset($data['cc'])) { $advertisement->setCc((int)$data['cc']);}

             if(isset($data['tires_front'])) { $advertisement->setTiresFront($data['tires_front']);}
             if(isset($data['tires_back'])) { $advertisement->setTiresBack($data['tires_back']);}
             if(isset($data['color'])) { $advertisement->setColor($data['color']);}
             if(isset($data['freeAccidents'])) { $advertisement->setFreeAccidents($data['freeAccidents']);}
             if(isset($data['gearbox'])) { $advertisement->setGearbox($data['gearbox']);}
             if(isset($data['number_of_gears'])) { $advertisement->setNumberOfGears(intval($data['number_of_gears']));}
             if(isset($data['doors'])) { $advertisement->setDoors(intval($data['doors']));}
             if(isset($data['seats'])) { $advertisement->setSeats(intval($data['seats']));}
             if(isset($data['environmentalBadge'])) { $advertisement->setEnvironmentalBadge($data['environmentalBadge']);}
             if(isset($data['warrantyDuration'])) { $advertisement->setWarrantyDuration(intval($data['warrantyDuration']));}

             //  ToDo: Validar si el anuncio está en el sitio especificado, si no está agregarlo

             $em->persist($advertisement);
             return $advertisement;
         }
         // ToDo: Mostrar error en pantalla y log , pasar el output
         return null;
    }


    static function validateArray(array $data): bool {

        return isset(
            $data['id'], $data['published'], $data['available'],
            $data['name'], $data['make'], $data['model'],
            $data['version'], $data['finish'], $data['vin'],
            $data['plate'],
            //$data['finish'], $data['vin'],
            // ToDo: Agregar otros campos que siempre deben venir para asumir que el arreglo contiene datos válidos.
        );

    }

}