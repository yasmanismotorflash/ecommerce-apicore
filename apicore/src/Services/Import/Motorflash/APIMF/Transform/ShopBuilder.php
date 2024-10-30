<?php
namespace App\Services\Import\Motorflash\APIMF\Transform;

use App\Entity\Shop;

class ShopBuilder implements BuilderInterface
{
    private $entity;

    private $json = '"shop": {
            "id": 992,
            "user": 4757,
            "name": "Autos Juanjo",
            "address": "Calle Toledo, Nº 40",
            "cp": "28981",
            "city": "PARLA",
            "provinceId": "M",
            "province": "Madrid",
            "country": "ES",
            "phone": "916991593",
            "email": "vo@autosjuanjo.es",
            "lt": "40.23009400",
            "lng": "-3.77702500"
        }';


    public function __construct() {
        $this->entity = new Shop();
    }






    public function fromJson(string $json): self {
        $data = json_decode($json, true);



        $this->entity->setProperty1($data['property1']);
        // Otros setters
        return $this;
    }

    public function build(): Shop {
        return $this->entity;
    }
}