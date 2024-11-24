<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'models', options: ["comment" => "Tabla para almacenar los modelos de los anuncios"])]
#[ApiResource(
    description: 'Entidad para manejar la información del modelo',
    operations: [
        new Get(),
        new GetCollection()
    ],
    paginationItemsPerPage: 40

)]
class Model
{
    #[Groups('ads:read')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[Groups('ads:read')]
    #[ORM\Column(type: 'string', name: 'name',length: 120, options: ["comment" => "Campo nombre visible del modelo"])]
    private string $name;

    #[Groups('ads:read')]
    #[ORM\ManyToOne(targetEntity: Make::class, inversedBy: 'models')]
    private Make $make;


    /**
     * @var Collection<int, Model>
     */
    #[ORM\OneToMany(targetEntity: Version::class, mappedBy: 'model')]
    private Collection $versions;

    /**
     * @var Collection<int, Advertisement>
     */
    #[ORM\OneToMany(targetEntity: Advertisement::class, mappedBy: 'model')]
    private Collection $advertisements;

    /**
     * @var Collection<int, Site>
     */
    #[ORM\ManyToMany(targetEntity: Site::class, mappedBy: 'models')]
    private Collection $sites;




    public function __construct()
    {
        $this->versions = new ArrayCollection();
        $this->advertisements = new ArrayCollection();
        $this->sites = new ArrayCollection();
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
     * @return Collection<int, Advertisement>
     */
    public function getAdvertisements(): Collection
    {
        return $this->advertisements;
    }

    public function addAdvertisement(Advertisement $advertisement): static
    {
        if (!$this->advertisements->contains($advertisement)) {
            $this->advertisements->add($advertisement);
            $advertisement->setModel($this);
        }

        return $this;
    }

    public function removeAdvertisement(Advertisement $advertisement): static
    {
        if ($this->advertisements->removeElement($advertisement)) {
            // set the owning side to null (unless already changed)
            if ($advertisement->getModel() === $this) {
                $advertisement->setModel(null);
            }
        }

        return $this;
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
            $site->addModel($this);
        }

        return $this;
    }

    public function removeSite(Site $site): static
    {
        if ($this->sites->removeElement($site)) {
            $site->removeModel($this);
        }

        return $this;
    }



}