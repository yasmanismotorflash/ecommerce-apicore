<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity]
#[ORM\Table(name: 'versions')]
#[ApiResource]
class Version
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Model::class, inversedBy: 'versions')]
    private Model $model;

    #[ORM\Column(type: 'string', length: 200)]
    private string $name;


    /**
     * @return int
     */
    public function getId():int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param Model $model
     * @return Version
     */
    public function setModel( Model $model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Version
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function __toString():string
    {
        return $this->name;
    }



}