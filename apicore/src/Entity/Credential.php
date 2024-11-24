<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\CredentialRepository;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CredentialRepository::class)]
#[ORM\Table(name: 'credentials', options: ["comment" => "Tabla para almacenar las credenciales de acceso a APICORE"])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(

    normalizationContext:[
        'groups'=>[ 'user:read'   ]
    ],
    denormalizationContext:[
        'groups'=>[ 'user:write'  ]
    ],

)]

class Credential implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['user:read','user:write'])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 9,max: 120)]
    #[Assert\Email]
    #[ORM\Column(type: 'string', name: 'email', length: 120, unique: true, options: ["comment" => "Campo email de la cuenta de acceso"])]
    private ?string $email = null;


    /**
     * @var string The hashed password
     */
    #[Groups('user:write')]
    #[Assert\Length(min: 3,max: 200)]
    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', name: 'password',length:200, nullable: false, options: ["comment" => "Campo password cifrado"])]
    private ?string $password = null;

    /**
     * @var list<string> The user roles
     */
    #[Groups(['user:read','user:write'])]
    #[ORM\Column( name: 'roles', options: ["comment" => "Campo roles, json con roles de la credencial"])]
    private array $roles = [];

    #[Groups(['user:read','user:write'])]
    #[ORM\Column(length: 80, unique: true, nullable: false, name: 'username', options: ["comment" => "Campo username para almecenar el nombre del usuario de la credencial"])]
    private ?string $username = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }
}
