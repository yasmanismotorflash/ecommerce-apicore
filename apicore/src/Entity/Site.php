<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SiteRepository::class)]
#[ORM\Table(name: 'sites')]
#[ApiResource]
class Site
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $name = null;

    #[ORM\Column(length: 150)]
    private ?string $url = null;

    #[ORM\Column]
    private ?int $mfSiteId = null;

    #[ORM\Column(length: 200)]
    private ?string $apimfClientId = null;

    #[ORM\Column(length: 200)]
    private ?string $apimfClientSecret = null;

    #[ORM\Column(length: 200)]
    private ?string $apicoreClientId = null;

    #[ORM\Column(length: 200)]
    private ?string $apicoreClientSecret = null;

    #[ORM\Column]
    private ?bool $active = null;

    /**
     * @var Collection<int, Advertisement>
     */
    #[ORM\ManyToMany(targetEntity: Advertisement::class, inversedBy: 'sites')]
    private Collection $advertisements;

    /**
     * @var Collection<int, Dealer>
     */
    #[ORM\ManyToMany(targetEntity: Dealer::class, mappedBy: 'sites')]
    private Collection $dealers;

    /**
     * @var Collection<int, Shop>
     */
    #[ORM\ManyToMany(targetEntity: Shop::class, mappedBy: 'sites')]
    private Collection $shops;

    /**
     * @var Collection<int, Make>
     */
    #[ORM\ManyToMany(targetEntity: Make::class, mappedBy: 'sites')]
    private Collection $makes;

    /**
     * @var Collection<int, Model>
     */
    #[ORM\ManyToMany(targetEntity: Model::class, mappedBy: 'sites')]
    private Collection $models;

    /**
     * @var Collection<int, Version>
     */
    #[ORM\ManyToMany(targetEntity: Version::class, mappedBy: 'sites')]
    private Collection $versions;

    public function __construct()
    {
        $this->advertisements = new ArrayCollection();
        $this->dealers = new ArrayCollection();
        $this->shops = new ArrayCollection();
        $this->makes = new ArrayCollection();
        $this->models = new ArrayCollection();
        $this->versions = new ArrayCollection();
    }

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    public function getApimfClientId(): ?string
    {
        return $this->apimfClientId;
    }

    public function setApimfClientId(string $apimfClientId): static
    {
        $this->apimfClientId = $apimfClientId;
        return $this;
    }

    public function getApimfClientSecret(): ?string
    {
        return $this->apimfClientSecret;
    }

    public function setApimfClientSecret(string $apimfClientSecret): static
    {
        $this->apimfClientSecret = $apimfClientSecret;
        return $this;
    }

    public function getApicoreClientId(): ?string
    {
        return $this->apicoreClientId;
    }

    public function setApicoreClientId(string $apicoreClientId): static
    {
        $this->apicoreClientId = $apicoreClientId;
        return $this;
    }

    public function getApicoreClientSecret(): ?string
    {
        return $this->apicoreClientSecret;
    }

    public function setApicoreClientSecret(string $apicoreClientSecret): static
    {
        $this->apicoreClientSecret = $apicoreClientSecret;
        return $this;
    }



    public function getMfSiteId(): ?int
    {
        return $this->mfSiteId;
    }

    public function setMfSiteId(int $mfSiteId): static
    {
        $this->mfSiteId = $mfSiteId;
        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;
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
        }

        return $this;
    }

    public function removeAdvertisement(Advertisement $advertisement): static
    {
        $this->advertisements->removeElement($advertisement);

        return $this;
    }

    /**
     * @return Collection<int, Dealer>
     */
    public function getDealers(): Collection
    {
        return $this->dealers;
    }

    public function addDealer(Dealer $dealer): static
    {
        if (!$this->dealers->contains($dealer)) {
            $this->dealers->add($dealer);
            $dealer->addSite($this);
        }

        return $this;
    }

    public function removeDealer(Dealer $dealer): static
    {
        if ($this->dealers->removeElement($dealer)) {
            $dealer->removeSite($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Shop>
     */
    public function getShops(): Collection
    {
        return $this->shops;
    }

    public function addShop(Shop $shop): static
    {
        if (!$this->shops->contains($shop)) {
            $this->shops->add($shop);
            $shop->addSite($this);
        }

        return $this;
    }

    public function removeShop(Shop $shop): static
    {
        if ($this->shops->removeElement($shop)) {
            $shop->removeSite($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Make>
     */
    public function getMakes(): Collection
    {
        return $this->makes;
    }

    public function addMake(Make $make): static
    {
        if (!$this->makes->contains($make)) {
            $this->makes->add($make);
            $make->addSite($this);
        }

        return $this;
    }

    public function removeMake(Make $make): static
    {
        if ($this->makes->removeElement($make)) {
            $make->removeSite($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Model>
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    public function addModel(Model $model): static
    {
        if (!$this->models->contains($model)) {
            $this->models->add($model);
            $model->addSite($this);
        }

        return $this;
    }

    public function removeModel(Model $model): static
    {
        if ($this->models->removeElement($model)) {
            $model->removeSite($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Version>
     */
    public function getVersions(): Collection
    {
        return $this->versions;
    }

    public function addVersion(Version $version): static
    {
        if (!$this->versions->contains($version)) {
            $this->versions->add($version);
            $version->addSite($this);
        }

        return $this;
    }

    public function removeVersion(Version $version): static
    {
        if ($this->versions->removeElement($version)) {
            $version->removeSite($this);
        }

        return $this;
    }


}
