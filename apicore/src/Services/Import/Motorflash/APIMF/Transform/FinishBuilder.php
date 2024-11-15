<?php
namespace App\Services\Import\Motorflash\APIMF\Transform;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Site;
use App\Entity\Model;
use App\Entity\Finish;
class  FinishBuilder
{

    static function getFinish(EntityManagerInterface $em, Site $site, Model $model, ?string $data): ?Finish {
        if($data) {
            $finish = $em->getRepository(Finish::class)->findOneByName($data);
            if($finish){
                return FinishBuilder::updateFinish($em, $site, $model,$finish, $data);
            }
            return FinishBuilder::createFinish($em, $site, $model, $data);
        }
        return null;
    }


    private static function updateFinish(EntityManagerInterface $em, Site $site, Model $model, Finish $finish, ?string $data): ?Finish
    {
        if($data) {
            // ToDo: Actualizar entidad con todos los datos disponibles en el arreglo y llenar el registro de cambios
            $finish->addSite($site);
            $finish->setModel($model);
            $finish->setName($data);
            return $finish;
        }
        // ToDo: Mostrar error en pantalla y log , pasar el output
        return null;
    }


    private static function createFinish(EntityManagerInterface $em, Site $site, Model $model,?string $data): ?Finish
    {
        if($data) {
            // ToDo: Crear entidad con todos los datos disponibles en el arreglo

            $finish = new Finish();
            $finish->addSite($site);
            $finish->setModel($model);
            $finish->setName($data);

            //  ToDo: Validar si el dealer está en el sitio especificado, si no está agregarlo

            $em->persist($finish);
            return $finish;
        }
        // ToDo: Mostrar error en pantalla y log , pasar el output
        return null;
    }

}