<?php
namespace App\Services\Import\Motorflash\APIMF\Transform;

use Doctrine\ORM\EntityManagerInterface;

interface BuilderInterface
{
    static function validateJson(string $json): bool;

    static function validateArray(array $data): bool;

    static function buildFromJson(string $json): ?object;

    static function buildFromArray(EntityManagerInterface $em, array $data): ?object;

}