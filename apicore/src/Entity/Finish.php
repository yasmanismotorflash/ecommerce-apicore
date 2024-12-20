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
#[ORM\Table(name: 'finishs', options: ["comment" => "Tabla para almacenar los acabados de los anuncios"])]
#[ApiResource(
    description: 'Entidad para manejar la información de los acabados.',
    operations: [
        new Get(),
        new GetCollection()
    ],
    paginationItemsPerPage: 40

)]
class Finish
{
    #[Groups('ads:read')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Model::class)]
    private Model $model;

    #[Groups('ads:read')]
    #[ORM\Column(type: 'string', name: 'name',length: 50, options: ["comment" => "Campo nombre visible del acabado"])]
    private string $name;

    /**
     * @var Collection<int, Advertisement>
     */
    #[ORM\OneToMany(targetEntity: Advertisement::class, mappedBy: 'finish')]
    private Collection $advertisements;

    /**
     * @var Collection<int, Site>
     */
    #[ORM\ManyToMany(targetEntity: Site::class, mappedBy: 'finishs')]
    private Collection $sites;


    public function __construct() {
        $this->advertisements = new ArrayCollection();
        $this->sites = new ArrayCollection();
    }



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
            $advertisement->setFinish($this);
        }

        return $this;
    }

    public function removeAdvertisement(Advertisement $advertisement): static
    {
        if ($this->advertisements->removeElement($advertisement)) {
            // set the owning side to null (unless already changed)
            if ($advertisement->getFinish() === $this) {
                $advertisement->setFinish(null);
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
            $site->addFinish($this);
        }

        return $this;
    }

    public function removeSite(Site $site): static
    {
        if ($this->sites->removeElement($site)) {
            $site->removeFinish($this);
        }

        return $this;
    }


}