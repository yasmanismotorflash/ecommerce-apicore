<?php

namespace App\Services\Import\Motorflash\APIMF\Transform;

use App\Entity\Advertisement;

class AdvertisementBuilder implements BuilderInterface
{

    private $entity;

    public function __construct() {
        $this->entity = new Advertisement();
    }

    public function fromJson(string $json): self {
        $data = json_decode($json, true);
        $this->entity->setProperty1($data['property1']);
        // Otros setters
        return $this;
    }

    public function build(): Advertisement {
        return $this->entity;
    }

}