<?php

namespace App\Services\Import\Motorflash\APIMF\Transform;

interface BuilderInterface
{
    public function fromJson(string $json): self;
    public function build();

}