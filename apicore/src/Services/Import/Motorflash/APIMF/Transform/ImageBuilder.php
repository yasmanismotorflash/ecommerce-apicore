<?php
namespace App\Services\Import\Motorflash\APIMF\Transform;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Site;
use App\Entity\Image;

class ImageBuilder
{

    static function validateString(string $url): bool {

        if (''== $url)
            return false;
        return true;
    }

    static function buildFromString(string $url): ?Image {
        if( ImageBuilder::validateString($url)) {
            $entity = new Image();
            return $entity->setUrl($url);
        }
        return null;
    }


    private function processImages(array $imagesData): array
    {
        $images = [];
        foreach ($imagesData as $url) {
            $image = ImageBuilder::buildFromString($url);
            $images[] = $image;
        }
        return $images;
    }

}