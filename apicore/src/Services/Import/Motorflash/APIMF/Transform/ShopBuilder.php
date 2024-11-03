<?php
namespace App\Services\Import\Motorflash\APIMF\Transform;

use App\Entity\Shop;

class ShopBuilder implements BuilderInterface
{

    public static function  validateJson(string $json): bool {
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE)
            return false;
        return ShopBuilder::validateArray($data);
    }


    static function validateArray(array $data): bool {
        return isset($data['id'], $data['user'], $data['name'], $data['address'], $data['cp'], $data['city'],
            $data['provinceId'], $data['province'], $data['country'], $data['phone'], $data['email'], $data['lt'], $data['lng']);
    }


    public static function buildFromJson(string $json): ?Shop {

        if( ShopBuilder::validateJson($json)) {
            $data = json_decode($json, true);
            return ShopBuilder::buildFromArray($data);
        }
        return null;
    }


    static function buildFromArray(array $data): ?object {

        if (ShopBuilder::validateArray($data)){
            $entity = new Shop();

            return $entity->setMfid($data['id'])
                ->setUser($data['user'])
                ->setName($data['name'])
                ->setAddress($data['address'])
                ->setCp($data['cp'])
                ->setCity($data['city'])
                ->setProvinceId($data['provinceId'])
                ->setProvince($data['province'])
                ->setCountry($data['country'])
                ->setPhone($data['phone'])
                ->setEmail($data['email'])
                ->setLt($data['lt'])
                ->setLng($data['lng']);
        }

    }
}