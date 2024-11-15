<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity]
#[ORM\Table(name: 'dealers', options: ["comment" => "Tabla para almacenar dealers (Concesionarios)"])]
#[ApiResource]
class Dealer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer', name: 'mfid', options: ["comment" => "Campo mfid, contiene el id usado en motorflash"])]
    private int $mfid;

    #[ORM\Column(type: 'string', name: 'name',length: 200, options: ["comment" => "Campo nombre visible del dealer (Concesionario)"])]
    private string $name;

    #[ORM\Column(type: 'string', name: 'type',length: 200, options: ["comment" => "Campo tipo de dealer (Concesionario)"])]
    private string $type;



    /**
     * @var Collection<int, Shop>
     */
    #[ORM\OneToMany(targetEntity: Shop::class, mappedBy: 'dealer' )]
    private Collection $shops;

    /**
     * @var Collection<int, Advertisement>
     */
    #[ORM\OneToMany(targetEntity: Advertisement::class, mappedBy: 'dealer')]
    private Collection $advertisements;

    /**
     * @var Collection<int, Site>
     */
    #[ORM\ManyToMany(targetEntity: Site::class, mappedBy: 'dealers')]
    private Collection $sites;

    public function __construct()
    {
        $this->shops = new ArrayCollection();
        $this->advertisements = new ArrayCollection();
        $this->sites = new ArrayCollection();
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Dealer
     */
    public function setId(?int $id): Dealer
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getMfid(): int
    {
        return $this->mfid;
    }

    /**
     * @param int $mfid
     * @return Dealer
     */
    public function setMfid(int $mfid): Dealer
    {
        $this->mfid = $mfid;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Dealer
     */
    public function setName(string $name): Dealer
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Dealer
     */
    public function setType(string $type): Dealer
    {
        $this->type = $type;
        return $this;
    }


    public function toJson(): string
    {
        return json_encode(array(
            "dealer" => array(
                "id" => $this->getId(),
                "mfid" => $this->getMfid(),
                "name" => $this->getName(),
                "type" => $this->getType()
            )
        ));
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
            $advertisement->setDealer($this);
        }

        return $this;
    }

    public function removeAdvertisement(Advertisement $advertisement): static
    {
        if ($this->advertisements->removeElement($advertisement)) {
            // set the owning side to null (unless already changed)
            if ($advertisement->getDealer() === $this) {
                $advertisement->setDealer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Shop>
     */
    public function getShops(): ?Collection
    {
        return $this->shops;
    }

    public function addShop(Shop $shop): static
    {
        if (!$this->shops->contains($shop)) {
            $this->shops->add($shop);
            $shop->setDealer($this);
        }

        return $this;
    }

    public function removeShop(Shop $shop): static
    {
        if ($this->shops->removeElement($shop)) {
            // set the owning side to null (unless already changed)
            if ($shop->getDealer() === $this) {
                $shop->setDealer(null);
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
            $site->addDealer($this);
        }

        return $this;
    }

    public function removeSite(Site $site): static
    {
        if ($this->sites->removeElement($site)) {
            $site->removeDealer($this);
        }

        return $this;
    }

}