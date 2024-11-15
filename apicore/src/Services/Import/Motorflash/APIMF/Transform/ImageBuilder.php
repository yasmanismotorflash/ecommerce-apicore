<?php
namespace App\Services\Import\Motorflash\APIMF\Transform;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Image;

class ImageBuilder
{

    static function createImage(EntityManagerInterface $em, ?string $data): ?Image
    {
        if($data) {
            // ToDo: Crear entidad con todos los datos disponibles en el arreglo
            $image = new Image();
            $image->setUrl($data);
            $em->persist($image);
            return $image;
        }
        // ToDo: Mostrar error en pantalla y log , pasar el output
        return null;
    }




    static function loadImages(EntityManagerInterface $em, array $imagesUrlList): array
    {
        $images = [];
        foreach ($imagesUrlList as $imageUrl) {
            $images[] = ImageBuilder::createImage($em, $imageUrl);
        }
        return $images;
    }

}