<?php

namespace App\Entity;

use App\Repository\FuelRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: FuelRepository::class)]
class Fuel
{
    #[Groups('ads:read')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups('ads:read')]
    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[Groups('ads:read')]
    #[ORM\Column]
    private ?int $mfid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getMfid(): ?int
    {
        return $this->mfid;
    }

    public function setMfid(int $mfid): static
    {
        $this->mfid = $mfid;

        return $this;
    }
}
