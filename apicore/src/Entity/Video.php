<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VideoRepository;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
#[ORM\Table(name: 'videos', options: ["comment" => "Tabla para almacenar los videos de los anuncios"])]
#[ApiResource]
class Video
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', name: 'url',length: 300, options: ["comment" => "Campo url del video"])]
    private ?string $url = null;

    #[ORM\Column(type: 'string', name: 'urlhash',length: 40, options: ["comment" => "Campo hash de la url del video"])]
    private ?string $urlhash = null;



    public function getId(): ?int
    {
        return $this->id;
    }


    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): Video
    {
        $this->url = $url;
        $this->urlhash = md5($url);
        return $this;
    }

    public function getUrlhash(): ?string
    {
        return $this->urlhash;
    }

    public function setUrlhash(string $urlhash): Video
    {
        $this->urlhash = $urlhash;
        return $this;
    }



}
