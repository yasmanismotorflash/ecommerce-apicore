<?php
namespace App\Services\Import\Motorflash\APIMF\Transform;

use App\Entity\Dealer;

class  DealerBuilder implements BuilderInterface
{

    static function validateJson(string $json): bool {
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE)
            return false;
        return DealerBuilder::validateArray($data);
    }


    static function validateArray(array $data): bool {
        return isset($data['id'], $data['name'], $data['type']);
    }


    public static function buildFromJson(string $json): ?Dealer {

        if( DealerBuilder::validateJson($json)) {
            $data = json_decode($json, true);
            return DealerBuilder::buildFromArray($data);
        }
        return null;
    }

    static function buildFromArray(array $data): ?Dealer {
        if( DealerBuilder::validateArray($data)) {
            $entity = new Dealer();
            return $entity->setMfid($data['id'])
                ->setName($data['name'])
                ->setType($data['type']);
        }
    }

}