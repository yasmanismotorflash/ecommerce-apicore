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


}
