<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity]
#[ORM\Table(name: 'makes')]
#[ApiResource]
class Make
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 200, unique: true)]
    private string $name;

    /**
     * @var Collection<int, Model>
     */
    #[ORM\OneToMany(targetEntity: Model::class, mappedBy: 'make')]
    private Collection $models;

    public function __construct()
    {
        $this->models = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId():int
    {
        return $this->id;
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
     * @return Make
     */
    public function setName(string $name):string
    {
        $this->name = $name;
        return $this;
    }

    public function getModels(): Collection
    {
        return $this->models;
    }

    public function setModels(Collection $models): Make
    {
        $this->models = $models;
        return $this;
    }

    public function __toString():string
    {
        return $this->name;
    }


}