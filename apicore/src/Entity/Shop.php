<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity]
#[ApiResource]
class Shop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'mfid', type: 'integer')]
    private int $mfid;

    #[ORM\Column(type: 'integer')]
    private int $user;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $address;

    #[ORM\Column(type: 'string', length: 10)]
    private string $cp;

    #[ORM\Column(type: 'string', length: 100)]
    private string $city;

    #[ORM\Column(type: 'string', length: 2)]
    private string $provinceId;

    #[ORM\Column(type: 'string', length: 100)]
    private string $province;

    #[ORM\Column(type: 'string', length: 2)]
    private string $country;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $phone;

    #[ORM\Column(type: 'string', length: 100)]
    private string $email;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 8, nullable: true)]
    private ?float $lt;

    #[ORM\Column(type: 'decimal', precision: 11, scale: 8, nullable: true)]
    private ?float $lng;

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

    /**
     * @return int
     */
    public function getUser(): int
    {
        return $this->user;
    }

    /**
     * @param int $user
     * @return Shop
     */
    public function setUser(int $user): Shop
    {
        $this->user = $user;
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
        return $this->lt;
    }

    /**
     * @param float|null $lt
     * @return Shop
     */
    public function setLt(?float $lt): Shop
    {
        $this->lt = $lt;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getLng(): ?float
    {
        return $this->lng;
    }

    /**
     * @param float|null $lng
     * @return Shop
     */
    public function setLng(?float $lng): Shop
    {
        $this->lng = $lng;
        return $this;
    }


}
