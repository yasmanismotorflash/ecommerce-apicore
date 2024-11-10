<?php

namespace App\Entity;

use App\Repository\RolRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RolRepository::class)]
#[ORM\Table(name: 'roles', options: ["comment" => "Tabla para almacenar los roles de las credenciales de acceso a APICORE"])]
class Rol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', name: 'name', length: 80, options: ["comment" => "Campo nombre visible del rol"])]
    private ?string $name = null;

    #[ORM\Column(type: 'string', name:'rol', length: 80, options: ["comment" => "Campo nombre interno del rol"])]
    private ?string $rol = null;

    #[ORM\Column(type: 'string', name:'description', length: 255, nullable: true, options: ["comment" => "Campo nombre interno del rol"])]
    private ?string $description = null;


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

    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(string $rol): static
    {
        $this->rol = $rol;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }
}
