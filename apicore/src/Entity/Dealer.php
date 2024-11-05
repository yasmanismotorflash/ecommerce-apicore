<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity]
#[ORM\Table(name: 'dealers')]
#[ApiResource]
class Dealer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'mfid', type: 'integer')]
    private int $mfid;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $type;

    #[ORM\OneToMany(targetEntity: Advertisement::class, mappedBy: 'dealer', cascade: ['persist'])]
    private Collection $advertisements;

    public function __construct()
    {
        $this->advertisements = new ArrayCollection();
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

    public function fromJson(string $json):void
    {
        $data = json_decode($json, true);
        $this->setMfid($data['dealer']['mfid']);
        $this->setName($data['dealer']['name']);
        $this->setType($data['dealer']['type']);
    }

}