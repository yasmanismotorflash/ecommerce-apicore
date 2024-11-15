<?php
namespace App\Services\Import\Motorflash\APIMF\Transform;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Site;
use App\Entity\Make;
use App\Entity\Model;

class  ModelBuilder
{

    static function getModel(EntityManagerInterface $em, Site $site, Make $make,?string $data): ?Model {
        if($data) {
            $model = $em->getRepository(Model::class)->findOneByName($data);
            if($model){
                return ModelBuilder::updateModel($em, $site, $make,$model, $data);
            }
            return ModelBuilder::createModel($em, $site, $make, $data);
        }
        return null;
    }


    private static function updateModel(EntityManagerInterface $em, Site $site, Make $make, Model $model, ?string $data): ?Model
    {
        if($data) {
            // ToDo: Actualizar entidad con todos los datos disponibles en el arreglo y llenar el registro de cambios
            $model->addSite($site);
            $model->setMake($make);
            $model->setName($data);
            return $model;
        }
        // ToDo: Mostrar error en pantalla y log , pasar el output
        return null;
    }


    private static function createModel(EntityManagerInterface $em, Site $site, Make $make ,?string $data): ?Model
    {
        if($data) {
            // ToDo: Crear entidad con todos los datos disponibles en el arreglo

            $model = new Model();
            $model->addSite($site);
            $model->setMake($make);
            $model->setName($data);

            //  ToDo: Validar si el dealer está en el sitio especificado, si no está agregarlo

            $em->persist($model);
            return $model;
        }
        // ToDo: Mostrar error en pantalla y log , pasar el output
        return null;
    }

}