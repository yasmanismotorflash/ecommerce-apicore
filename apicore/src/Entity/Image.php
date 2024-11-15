<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ImageRepository;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ORM\Table(name: 'images', options: ["comment" => "Tabla para almacenar las imágenes de los anuncios"])]
#[ApiResource]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', name: 'url',length: 300, options: ["comment" => "Campo url de la imágen"])]
    private ?string $url = null;


    #[ORM\Column(type: 'string', name: 'urlhash',length: 40, options: ["comment" => "Campo hash de la url de la imágen"])]
    private ?string $urlhash = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Advertisement $advertisement = null;



    public function getId(): ?int {
        return $this->id;
    }


    public function getUrl(): ?string {
        return $this->url;
    }

    public function setUrl(string $url): Image {
        $this->url = $url;
        $this->urlhash = md5($url);
        return $this;
    }

    public function getUrlhash(): ?string
    {
        return $this->urlhash;
    }

    public function setUrlhash(string $urlhash): Image
    {
        $this->urlhash = $urlhash;
        return $this;
    }

    public function getAdvertisement(): ?Advertisement
    {
        return $this->advertisement;
    }

    public function setAdvertisement(?Advertisement $advertisement): static
    {
        $this->advertisement = $advertisement;
        return $this;
    }


}
