<?php
namespace App\Entity;

use App\Repository\ConfigurationParameterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigurationParameterRepository::class)]
#[ORM\Table(name: "configuration_parameter")]
#[ORM\Index(columns: ["name"], name: "idx_nombre_paramt_config")]
class ConfigurationParameter
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
     * @return ConfigurationParameter
     */
    public function initialize(string $name, string $type, ?string $valueStr): ConfigurationParameter
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
     * @return ConfigurationParameter
     */
    public function setName(string $name): ConfigurationParameter
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
     * @return ConfigurationParameter
     */
    public function setType(string $type): ConfigurationParameter
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
     * @return ConfigurationParameter
     */
    public function setValueStr(?string $valueStr): ConfigurationParameter
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
