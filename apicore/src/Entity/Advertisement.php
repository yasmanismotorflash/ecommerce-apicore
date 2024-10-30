<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity]
#[ORM\Table(name: 'advertisement')]
#[ApiResource]

class Advertisement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'mfid', type: 'integer')]
    private int $mfid;

    #[ORM\Column(type: 'string', length: 20)]
    private string $published;

    #[ORM\Column(type: 'string', length: 50)]
    private string $available;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\Column(type: 'string', length: 50)]
    private string $make;

    #[ORM\Column(type: 'string', length: 50)]
    private string $model;

    #[ORM\Column(type: 'string', length: 50)]
    private string $version;

    #[ORM\Column(type: 'string', length: 100)]
    private string $finish;

    #[ORM\Column(type: 'string', length: 17)]
    private string $vin;

    #[ORM\Column(type: 'string', length: 20)]
    private string $plate;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $registrationDate;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $lastRegistrationDate;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $manufacturingDate;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $publicationDate;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $modificationDate;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $lastUpdate;

    #[ORM\Column(type: 'integer')]
    private int $daysPublished;

    #[ORM\Column(type: 'string', length: 20)]
    private string $jato;

    #[ORM\Column(type: 'string', length: 20)]
    private string $typnatcode;

    #[ORM\Column(type: 'string', length: 50)]
    private string $internalRef;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $origen;

    #[ORM\Column(type: 'string', length: 50)]
    private string $status;

    #[ORM\Column(type: 'string', length: 50)]
    private string $typeVehicle;

    #[ORM\Column(type: 'string', length: 50)]
    private string $bodyType;

    #[ORM\Column(type: 'string', length: 50)]
    private string $bodyTypeEs;

    #[ORM\Column(type: 'boolean')]
    private bool $iva;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $price;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $financedPrice;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $purchasePrice;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $priceNew;


    #[ORM\Column(type: 'integer')]
    private int $km;

    #[ORM\Column(type: 'string', length: 10)]
    private string $cv;

    #[ORM\Column(type: 'string', length: 10)]
    private string $kw;

    #[ORM\Column(type: 'integer')]
    private int $cc;

    #[ORM\Column(type: 'string', length: 50)]
    private string $tires_front;

    #[ORM\Column(type: 'string', length: 50)]
    private string $tires_back;

    #[ORM\Column(type: 'string', length: 50)]
    private string $fuel;

    #[ORM\Column(type: 'string', length: 50)]
    private string $emissions_name;


    #[ORM\Column(type: 'decimal', precision: 5, scale: 1)]
    private float $combinedConsumption;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $autonomy_value;

    #[ORM\Column(type: 'string', length: 20)]
    private string $topSpeed_value;

    #[ORM\Column(type: 'string', length: 20)]
    private string $maxAcceleration_value;

    #[ORM\Column(type: 'string', length: 50)]
    private string $color;

    #[ORM\Column(type: 'boolean')]
    private bool $freeAccidents;

    #[ORM\Column(type: 'string', length: 20)]
    private string $width_value;

    #[ORM\Column(type: 'string', length: 20)]
    private string $length_value;

    #[ORM\Column(type: 'string', length: 20)]
    private string $height_value;

    #[ORM\Column(type: 'string', length: 20)]
    private string $wheelbase_value;

    #[ORM\Column(type: 'string', length: 20)]
    private string $weight_value;

    #[ORM\Column(type: 'string', length: 20)]
    private string $maxWeight_value;

    #[ORM\Column(type: 'string', length: 20)]
    private string $maxTorque_value;

    #[ORM\Column(type: 'string', length: 20)]
    private string $fuelTankCapacity_value;

    #[ORM\Column(type: 'string', length: 20)]
    private string $trunkCapacity_value;

    #[ORM\Column(type: 'string', length: 20)]
    private string $maxTrunkCapacity_value;

    #[ORM\Column(type: 'string', length: 20)]
    private string $batteryCapacity_value;

    #[ORM\Column(type: 'string', length: 50)]
    private string $traction;

    #[ORM\Column(type: 'string', length: 50)]
    private string $gearbox;

    #[ORM\Column(type: 'integer')]
    private int $number_of_gears;

    #[ORM\Column(type: 'integer')]
    private int $doors;

    #[ORM\Column(type: 'integer')]
    private int $seats;

    #[ORM\Column(type: 'string', length: 50)]
    private string $environmentalBadge;

    #[ORM\Column(type: 'integer')]
    private int $warrantyDuration;


    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $monthlyRate_cuota;

    #[ORM\Column(type: 'string', length: 50)]
    private string $monthlyRate_financiera_javascript;

    #[ORM\Column(type: 'integer')]
    private int $monthlyRate_id_site;

    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'advertisement', cascade: ['persist', 'remove'])]
    private Collection $images;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $video = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $quote = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $textLegal = null;


}
