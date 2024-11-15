<?php
namespace App\Services\Import\Motorflash\APIMF\Transform;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Site;
use App\Entity\Model;
use App\Entity\Version;
class  VersionBuilder
{

    static function getVersion(EntityManagerInterface $em, Site $site, Model $model, ?string $data): ?Version {
        if($data) {
            $version = $em->getRepository(Version::class)->findOneByName($data);
            if($version){
                return VersionBuilder::updateVersion($em, $site, $model,$version, $data);
            }
            return VersionBuilder::createVersion($em, $site, $model, $data);
        }
        return null;
    }


    private static function updateVersion(EntityManagerInterface $em, Site $site, Model $model, Version $version, ?string $data): ?Version
    {
        if($data) {
            // ToDo: Actualizar entidad con todos los datos disponibles en el arreglo y llenar el registro de cambios
            $version->addSite($site);
            $version->setModel($model);
            $version->setName($data);
            return $version;
        }
        // ToDo: Mostrar error en pantalla y log , pasar el output
        return null;
    }


    private static function createVersion(EntityManagerInterface $em, Site $site, Model $model,?string $data): ?Version
    {
        if($data) {
            // ToDo: Crear entidad con todos los datos disponibles en el arreglo

            $version = new Version();
            $version->addSite($site);
            $version->setModel($model);
            $version->setName($data);

            //  ToDo: Validar si el dealer está en el sitio especificado, si no está agregarlo

            $em->persist($version);
            return $version;
        }
        // ToDo: Mostrar error en pantalla y log , pasar el output
        return null;
    }

}