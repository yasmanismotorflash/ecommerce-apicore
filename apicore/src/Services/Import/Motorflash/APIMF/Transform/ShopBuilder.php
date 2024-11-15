<?php
namespace App\Services\Import\Motorflash\APIMF\Transform;

use App\Entity\Dealer;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Site;
use App\Entity\Shop;

class ShopBuilder
{
    static function getShop(EntityManagerInterface $em, Site $site, Dealer $dealer, ?array $data): ?Shop {
        if(isset($data['id'])) {
            $shop = $em->getRepository(Shop::class)->findOneByMfid($data['id']);
            if($shop){
                return ShopBuilder::updateShop($em, $site, $dealer, $shop, $data);
            }
            return ShopBuilder::createShop($em, $site, $dealer, $data);
        }
        return null;
    }



    private static function updateShop(EntityManagerInterface $em, Site $site, Dealer $dealer, Shop $shop, array $data): ?Shop
    {
        if(ShopBuilder::validateArray($data)) {
            $shop->setDealer($dealer);
            // ToDo: Actualizar entidad con todos los datos disponibles en el arreglo y llenar el registro de cambios
            if(isset($data['id'])) { $shop->setMfid($data['id']);}
            if(isset($data['user'])) { $shop->setDealerMfId($data['user']);}
            if(isset($data['name'])) { $shop->setName($data['name']);}
            if(isset($data['address'])) { $shop->setAddress($data['address']);}
            if(isset($data['cp'])) { $shop->setCp($data['cp']);}
            if(isset($data['city'])) { $shop->setCity($data['city']);}
            if(isset($data['provinceId'])) { $shop->setProvinceId($data['provinceId']);}
            if(isset($data['province'])) { $shop->setProvince($data['province']);}
            if(isset($data['country'])) { $shop->setCountry($data['country']);}
            if(isset($data['phone'])) { $shop->setPhone($data['phone']);}
            if(isset($data['email'])) { $shop->setEmail($data['email']);}
            if(isset($data['lt'])) { $shop->setLt(doubleval($data['lt']));}
            if(isset($data['lng'])) { $shop->setLng(doubleval($data['lng']));}

            return $shop;
        }
        // ToDo: Mostrar error en pantalla y log , pasar el output
        return null;
    }



    private static function createShop(EntityManagerInterface $em, Site $site, Dealer $dealer, array $data): ?Shop
    {
        if(ShopBuilder::validateArray($data)) {
            // ToDo: Crear entidad con todos los datos disponibles en el arreglo

            $shop = new Shop();
            $shop->addSite($site);
            $shop->setDealer($dealer);

            if(isset($data['id'])) { $shop->setMfid($data['id']);}
            if(isset($data['user'])) { $shop->setDealerMfId($data['user']);}
            if(isset($data['name'])) { $shop->setName($data['name']);}
            if(isset($data['address'])) { $shop->setAddress($data['address']);}
            if(isset($data['cp'])) { $shop->setCp($data['cp']);}
            if(isset($data['city'])) { $shop->setCity($data['city']);}
            if(isset($data['provinceId'])) { $shop->setProvinceId($data['provinceId']);}
            if(isset($data['province'])) { $shop->setProvince($data['province']);}
            if(isset($data['country'])) { $shop->setCountry($data['country']);}
            if(isset($data['phone'])) { $shop->setPhone($data['phone']);}
            if(isset($data['email'])) { $shop->setEmail($data['email']);}
            if(isset($data['lt'])) { $shop->setLt(doubleval($data['lt']));}
            if(isset($data['lng'])) { $shop->setLng(doubleval($data['lng']));}

            //  ToDo: Validar si el dealer está en el sitio especificado, si no está agregarlo

            $em->persist($shop);
            return $shop;
        }
        // ToDo: Mostrar error en pantalla y log , pasar el output
        return null;

    }



    static function validateArray(array $data): bool {
        return isset($data['id'], $data['user'], $data['name'], $data['address'], $data['cp'], $data['city'],
            $data['provinceId'], $data['province'], $data['country'], $data['phone'], $data['email'], $data['lt'], $data['lng']);
    }


}