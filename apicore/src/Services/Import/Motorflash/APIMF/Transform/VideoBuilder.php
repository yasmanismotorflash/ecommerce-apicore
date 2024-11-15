<?php
namespace App\Services\Import\Motorflash\APIMF\Transform;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Video;

class  VideoBuilder
{
    static function createVideo(EntityManagerInterface $em, ?string $data): ?Video
    {
        if($data) {
            // ToDo: Crear entidad con todos los datos disponibles en el arreglo
            $video = new Video();
            $video->setUrl($data);
            $em->persist($video);
            return $video;
        }
        // ToDo: Mostrar error en pantalla y log , pasar el output
        return null;
    }

}