<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity]
#[ORM\Table(name: 'shops', options: ["comment" => "Tabla para almacenar shops (Tiendas)"])]
#[ApiResource]
class Shop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'integer', name: 'mfid', options: ["comment" => "Campo mfid, contiene el id usado en motorflash"])]
    private int $mfid;


    #[ORM\Column(type: 'integer', name: 'dealermfid', options: ["comment" => "Campo dealermfid, contiene el id del dealer usado en motorflash"])]
    private int $dealermfid;

    #[ORM\Column(type: 'string', name: 'name',length: 120, options: ["comment" => "Campo nombre visible de la shop (Tienda)"])]
    private string $name;

    #[ORM\Column(type: 'string', name: 'address',length: 120, options: ["comment" => "Campo dirección de la shop (Tienda)"])]
    private string $address;

    #[ORM\Column(type: 'string', name: 'cp',length: 10, options: ["comment" => "Campo codigo postal de la shop (Tienda)"])]
    private string $cp;

    #[ORM\Column(type: 'string', name: 'city',length: 120, options: ["comment" => "Campo ciudad de la shop (Tienda)"])]
    private string $city;

    #[ORM\Column(type: 'string', name: 'provinceid',length: 5, options: ["comment" => "Campo id provincia de la shop (Tienda)"])]
    private string $provinceId;

    #[ORM\Column(type: 'string', name: 'province',length: 100, options: ["comment" => "Campo provincia de la shop (Tienda)"])]
    private string $province;

    #[ORM\Column(type: 'string', name: 'country',length: 100, options: ["comment" => "Campo código país de la shop (Tienda)"])]
    private string $country;

    #[ORM\Column(type: 'string', name: 'phone',length: 20, options: ["comment" => "Campo teléfono de la shop (Tienda)"])]
    private ?string $phone;

    #[ORM\Column(type: 'string', name: 'email',length: 120, options: ["comment" => "Campo email de la shop (Tienda)"])]
    private string $email;


    #[ORM\Column(type: 'decimal', name: 'lt', precision: 11, scale: 8, nullable: true, options: ["comment" => "Campo ubicación latitud de la shop (Tienda)"])]
    private ?string $lt;

    #[ORM\Column(type: 'decimal', name: 'lng', precision: 11, scale: 8, nullable: true, options: ["comment" => "Campo ubicación longitud de la shop (Tienda)"])]
    private ?string $lng;

    #[ORM\ManyToOne(inversedBy: 'shops')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Dealer $dealer = null;

    /**
     * @var Collection<int, Advertisement>
     */
    #[ORM\OneToMany(targetEntity: Advertisement::class, mappedBy: 'shop', orphanRemoval: true)]
    private Collection $advertisements;

    /**
     * @var Collection<int, Site>
     */
    #[ORM\ManyToMany(targetEntity: Site::class, mappedBy: 'shops')]
    private Collection $sites;


    public function __construct()
    {
        $this->advertisements = new ArrayCollection();
        $this->sites = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Shop
     */
    public function setId(int $id): Shop
    {
        $this->id = $id;
        return $this;
    }

    public function getMfid(): int
    {
        return $this->mfid;
    }

    public function setMfid(int $mfid): Shop
    {
        $this->mfid = $mfid;
        return $this;
    }

    /**
     * @return int
     */
    public function getDealerMfId(): int
    {
        return $this->dealermfid;
    }

    /**
     * @param int $dealermfid
     * @return Shop
     */
    public function setDealerMfId(int $dealermfid): Shop
    {
        $this->dealermfid = $dealermfid;
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
     * @return Shop
     */
    public function setName(string $name): Shop
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return Shop
     */
    public function setAddress(string $address): Shop
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getCp(): string
    {
        return $this->cp;
    }

    /**
     * @param string $cp
     * @return Shop
     */
    public function setCp(string $cp): Shop
    {
        $this->cp = $cp;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return Shop
     */
    public function setCity(string $city): Shop
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getProvinceId(): string
    {
        return $this->provinceId;
    }

    /**
     * @param string $provinceId
     * @return Shop
     */
    public function setProvinceId(string $provinceId): Shop
    {
        $this->provinceId = $provinceId;
        return $this;
    }

    /**
     * @return string
     */
    public function getProvince(): string
    {
        return $this->province;
    }

    /**
     * @param string $province
     * @return Shop
     */
    public function setProvince(string $province): Shop
    {
        $this->province = $province;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return Shop
     */
    public function setCountry(string $country): Shop
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return Shop
     */
    public function setPhone(?string $phone): Shop
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Shop
     */
    public function setEmail(string $email): Shop
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getLt(): ?float
    {
        return $this->lt !== null ? (float)$this->lt : null;
    }

    /**
     * @param float|null $lt
     * @return Shop
     */
    public function setLt(?float $lt): Shop
    {
        $this->lt = $lt !== null ? (string)$lt : null;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getLng(): ?float
    {
        return $this->lng !== null ? (float)$this->lng : null;
    }

    /**
     * @param float|null $lng
     * @return Shop
     */
    public function setLng(?float $lng): Shop
    {
        $this->lng = $lng !== null ? (string)$lng : null;
        return $this;
    }

    public function getDealer(): ?Dealer
    {
        return $this->dealer;
    }

    public function setDealer(?Dealer $dealer): static
    {
        $this->dealer = $dealer;

        return $this;
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
            $advertisement->setShop($this);
        }

        return $this;
    }

    public function removeAdvertisement(Advertisement $advertisement): static
    {
        if ($this->advertisements->removeElement($advertisement)) {
            // set the owning side to null (unless already changed)
            if ($advertisement->getShop() === $this) {
                $advertisement->setShop(null);
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
            $site->addShop($this);
        }

        return $this;
    }

    public function removeSite(Site $site): static
    {
        if ($this->sites->removeElement($site)) {
            $site->removeShop($this);
        }

        return $this;
    }



}
