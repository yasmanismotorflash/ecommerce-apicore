<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VideoRepository;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
#[ORM\Table(name: 'videos')]
#[ApiResource]
class Video
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $ad_id = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdId(): ?int
    {
        return $this->ad_id;
    }

    public function setAdId(int $ad_id): static
    {
        $this->ad_id = $ad_id;

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
}
