<?php
namespace App\Services\Import\Motorflash\APIMF\Transform;

use App\Entity\Image;

class  ImageBuilder
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
    }

}