<?php
namespace App\Services\Import\Motorflash\APIMF\Transform;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Site;
use App\Entity\Video;

class  VideoBuilder
{

    static function validateString(string $url): bool {

        if (''== $url)
            return false;
        return true;
    }

    static function buildFromString(string $url): ?Video {
        if( VideoBuilder::validateString($url)) {
            $entity = new Video();
            return $entity->setUrl($url);
        }
        return null;
    }

}