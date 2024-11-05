<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity]
#[ORM\Table(name: 'models')]
#[ApiResource]
class Model
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 200)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: Make::class, inversedBy: 'models')]
    private Make $make;

    /**
     * @var Collection<int, Model>
     */
    #[ORM\OneToMany(targetEntity: Version::class, mappedBy: 'model')]
    private Collection $versions;

    public function __construct()
    {
        $this->versions = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Model
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Make
     */
    public function getMake(): Make
    {
        return $this->make;
    }

    /**
     * @param mixed $make
     * @return Model
     */
    public function setMake($make)
    {
        $this->make = $make;
        return $this;
    }

    public function getVersions(): Collection
    {
        return $this->versions;
    }

    public function setVersions(Collection $versions): Model
    {
        $this->versions = $versions;
        return $this;
    }

    public function __toString():string
    {
        return $this->name;
    }


}