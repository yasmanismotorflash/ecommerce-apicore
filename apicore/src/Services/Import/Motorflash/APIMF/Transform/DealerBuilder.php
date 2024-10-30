<?php

namespace App\Services\Import\Motorflash\APIMF\Transform;

use App\Entity\Dealer;

class  DealerBuilder implements BuilderInterface
{
    private $entity;

    public function __construct()
    {
        $this->entity = new Dealer();
    }


    public function fromJson(string $json): self
    {
        $data = json_decode($json, true);
        $this->entity->setMfid($data['id'])
            ->setName($data['name'])
            ->setType($data['type']);
        return $this;
    }

    public function build(): Dealer
    {
        return $this->entity;
    }
}