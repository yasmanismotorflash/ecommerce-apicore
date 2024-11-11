<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity]
#[ORM\Table(name: 'models', options: ["comment" => "Tabla para almacenar los modelos de los anuncios"])]
#[ApiResource]
class Model
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', name: 'name',length: 120, options: ["comment" => "Campo nombre visible del modelo"])]
    private string $name;

    #[ORM\ManyToOne(targetEntity: Make::class, inversedBy: 'models')]
    private Make $make;

    /**
     * @var Collection<int, Site>
     */
    #[ORM\ManyToMany(targetEntity: Site::class, inversedBy: 'models')]
    private Collection $sites;

    /**
     * @var Collection<int, Model>
     */
    #[ORM\OneToMany(targetEntity: Version::class, mappedBy: 'model')]
    private Collection $versions;




    public function __construct()
    {
        $this->sites = new ArrayCollection();
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



    /**
     * @return Collection<int, Site>
     */
    public function getSites(): Collection
    {
        return $this->sites;
    }

    public function addSite(Site $site): static
    {
        if (!$this->sites->contains($site)) {
            $this->sites->add($site);
        }

        return $this;
    }

    public function removeSite(Site $site): static
    {
        $this->sites->removeElement($site);

        return $this;
    }


}