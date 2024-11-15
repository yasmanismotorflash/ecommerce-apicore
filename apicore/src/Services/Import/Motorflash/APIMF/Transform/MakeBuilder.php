<?php
namespace App\Services\Import\Motorflash\APIMF\Transform;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Site;
use App\Entity\Make;


class  MakeBuilder
{

    static function getMake(EntityManagerInterface $em, Site $site, ?string $data): ?Make {
        if($data) {
            $make = $em->getRepository(Make::class)->findOneByName($data);
            if($make){
                return MakeBuilder::updateMake($em, $site, $make, $data);
            }
            return MakeBuilder::createMake($em, $site, $data);
        }
        return null;
    }


    private static function updateMake(EntityManagerInterface $em, Site $site, Make $make, ?string $data): ?Make
    {
        if($data) {
            // ToDo: Actualizar entidad con todos los datos disponibles en el arreglo y llenar el registro de cambios
            $make ->setName($data);
            return $make;
        }
        // ToDo: Mostrar error en pantalla y log , pasar el output
        return null;
    }


    private static function createMake(EntityManagerInterface $em, Site $site, ?string $data): ?Make
    {
        if($data) {
            // ToDo: Crear entidad con todos los datos disponibles en el arreglo

            $make = new Make();

            $make->addSite($site);
            $make->setName($data);

            //  ToDo: Validar si el dealer está en el sitio especificado, si no está agregarlo

            $em->persist($make);
            return $make;
        }
        // ToDo: Mostrar error en pantalla y log , pasar el output
        return null;

    }

}