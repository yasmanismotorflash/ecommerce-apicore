<?php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ConfigValueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigValueRepository::class)]
#[ORM\Table(name: "config_values")]
#[ORM\Index(columns: ["name"], name: "idx_nombre_paramt_config")]
#[ApiResource]

class ConfigValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private ?string $name = null;

    /***
     * @var string Valores adminitidos ('int','integer','entero','float', 'flotante','decimal','double','string','json','boolean','bit')
     */
    #[ORM\Column(length: 20)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $valueStr = null;



    /**
     * @param string $name
     * @param string $type
     * @param string|null $valueStr
     * @return ConfigValue
     */
    public function initialize(string $name, string $type, ?string $valueStr): ConfigValue
    {
        $this->name = $name;
        $this->type = $type;
        $this->valueStr = $valueStr;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return ConfigValue
     */
    public function setName(string $name): ConfigValue
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
     * @return ConfigValue
     */
    public function setType(string $type): ConfigValue
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getValueStr(): ?string
    {
        return $this->valueStr;
    }

    /**
     * @param string|null $valueStr
     * @return ConfigValue
     */
    public function setValueStr(?string $valueStr): ConfigValue
    {
        $this->valueStr = $valueStr;
        return $this;
    }

    /**
     * Devuelve el valor según el tipo correspondiente.
     * @return int|float|string|array|bool
     */
    public function getValue(): int|float|string|array|bool
    {
        return match ($this->type) {
            'int','integer','entero' => (int) $this->valueStr,
            'float', 'flotante','decimal','double' => (float) $this->valueStr,
            'string','password' => (string) $this->valueStr,
            'json' => json_decode($this->valueStr, true),
            'boolean','bit' => (bool) filter_var($this->valueStr, FILTER_VALIDATE_BOOLEAN),
            default => (string) $this->valueStr,
        };
    }

    public function __toString(): string
    {
        return $this->name.' => '.$this->type.'('.$this->valueStr.')';
    }

}
