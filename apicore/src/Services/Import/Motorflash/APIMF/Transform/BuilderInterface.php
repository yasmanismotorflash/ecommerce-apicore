<?php
namespace App\Services\Import\Motorflash\APIMF\Transform;

interface BuilderInterface
{
    static function validateJson(string $json): bool;

    static function buildFromJson(string $json): ?object;

}