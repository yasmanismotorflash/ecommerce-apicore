<?php
namespace App\Services\Import\Motorflash\APIMF\Transform;

use App\Entity\Advertisement;
use App\Entity\Dealer;
use App\Entity\Shop;

class AdvertisementBuilder implements BuilderInterface
{

    static function validateJson(string $json): bool {
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE)
            return false;
        return AdvertisementBuilder::validateArray($data);
    }

    static function validateArray(array $data): bool {

        return isset(
            $data['id'], $data['published'], $data['available'],
            $data['name'], $data['make'], $data['model'],
            $data['version'], $data['finish'], $data['vin'],
            $data['plate'],
            //$data['finish'], $data['vin'],
        );

    }


    public static function buildFromJson(string $json): ?Advertisement {

        if( AdvertisementBuilder::validateJson($json)) {
            $data = json_decode($json, true);
            return AdvertisementBuilder::buildFromArray($data);
        }
        return null;
    }

    static function buildFromArray(array $data): ?object  {
        if( AdvertisementBuilder::validateArray($data)) {

            $entity = new Advertisement();

            return $entity->setMfid($data['id'])
                ->setPublished($data['published'])
                ->setAvailable($data['available'])
                ->setName($data['name'])
                ->setMake($data['make'])
                ->setModel($data['model'])
                ->setVersion($data['version'])
                ->setFinish($data['finish'])
                ->setVin($data['vin'])
                ->setPlate($data['plate'])
                ->setRegistrationDate(\DateTime::createFromFormat('Y-m-d', $data['registrationDate']))
                //->setLastRegistrationDate(\DateTime::createFromFormat('Y-m-d', $data['lastRegistrationDate']))
                //->setManufacturingDate(\DateTime::createFromFormat('Y-m-d', $data['manufacturingDate']))
                //->setPublicationDate(\DateTime::createFromFormat('Y-m-d', $data['publicationDate']))
                //->setModificationDate(\DateTime::createFromFormat('Y-m-d', $data['modificationDate']))
                //->setLastUpdate(\DateTime::createFromFormat('Y-m-d', $data['lastUpdate']))
                ->setDaysPublished($data['daysPublished'])
                ->setJato($data['jato'])
                ->setTypnatcode($data['typnatcode'])
                ->setInternalRef($data['internalRef'])
                ->setOrigen($data['origen'])
                //->setDealer(DealerBuilder::buildFromArray($data['dealer']))
                //->setShop(ShopBuilder::buildFromArray($data['shop']))
                ->setStatus($data['status'])
                ->setTypeVehicle($data['typeVehicle'])
                ->setBodyType($data['bodyType'])
                ->setBodyTypeEs($data['bodyTypeEs'])
                ->setIva($data['iva'])
                ->setPrice($data['price'])
                ->setFinancedPrice($data['financedPrice'])
                ->setPurchasePrice($data['purchasePrice'])
                //->setPriceNew($data['priceNew'])   parcear a float
                ->setKm($data['km'])
                ->setCv($data['cv'])
                ->setKw($data['kw'])
                ->setCc((int)($data['cc']))
                ->setTiresFront($data['tires_front'])
                ->setTiresBack($data['tires_back'])
                // ->setFuel($data['fuel'])    //ToDo Convertir a una entidad aparte

                ->setColor($data['color'])
                ->setFreeAccidents($data['freeAccidents'])
                ->setGearbox($data['gearbox'])
                ->setNumberOfGears($data['number_of_gears'])
                ->setDoors($data['doors'])
                ->setSeats($data['seats'])
                ->setEnvironmentalBadge($data['environmentalBadge'])

                ->setWarrantyDuration($data['warrantyDuration'])
                //->set
                ;

        }
        return null;

    }

}