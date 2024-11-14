<?php
namespace App\Services\Import\Motorflash\APIMF\Transform;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Site;
use App\Entity\Dealer;

class  DealerBuilder
{

    static function getDealer(EntityManagerInterface $em, Site $site, ?array $data): ?Dealer {
        if(isset($data['id'])) {
            $dealer = $em->getRepository(Dealer::class)->findOneByMfid($data['id']);
            if($dealer){
                return DealerBuilder::updateDealer($em, $site, $dealer, $data);
            }
            return DealerBuilder::createDealer($em, $site, $data);
        }
        return null;
    }


    private static function updateDealer(EntityManagerInterface $em, Site $site, Dealer $dealer, array $data): ?Dealer
    {
        if(DealerBuilder::validateArray($data)) {
            // ToDo: Actualizar entidad con todos los datos disponibles en el arreglo y llenar el registro de cambios


            return $dealer;
        }
        // ToDo: Mostrar error en pantalla y log , pasar el output
        return null;
    }


    private static function createDealer(EntityManagerInterface $em, Site $site, array $data): ?Dealer
    {
        if(DealerBuilder::validateArray($data)) {
            // ToDo: Crear entidad con todos los datos disponibles en el arreglo

            $dealer = new Dealer();
            if(isset($data['id'])) { $dealer->setMfid($data['id']);}
            if(isset($data['name'])) { $dealer->setName($data['name']);}
            if(isset($data['type'])) { $dealer->setType($data['type']);}

            //  ToDo: Validar si el dealer está en el sitio especificado, si no está agregarlo
            //$dealer->addSite($site);
            $em->persist($dealer);
            return $dealer;
        }
        // ToDo: Mostrar error en pantalla y log , pasar el output
        return null;

    }



    static function validateArray(array $data): bool {
        return isset($data['id'], $data['name'], $data['type']);
    }

}