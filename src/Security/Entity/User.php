<?php

namespace App\Security\Entity;

use App\Entity\Trait\Disableable;
use App\Entity\Trait\Identified;
use App\Entity\Trait\Timestampable;
use App\Security\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Translation\TranslatableMessage;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'client' => Client::class,
    'admin' => Admin::class
])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'An account with that email already exists')]
#[ORM\HasLifecycleCallbacks]
abstract class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const string PASSWORD_CHANGE_INTERVAL = '3 months';

    use Identified;
    use Disableable;
    use Timestampable;

    #[ORM\Column(length: 180)]
    protected ?string $email = null;
    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    protected array $roles = [];
    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    protected ?string $password = null;

    protected ?string $plainPassword = null;

    #[ORM\Column]
    protected ?string $name = null;

    #[ORM\Column]
    protected DateTimeImmutable $lastPasswordChange;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->lastPasswordChange = new DateTimeImmutable();
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
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->roles = $this->getRoles();
    }

    /**
     * @return list<string>
     * @see UserInterface
     *
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = UserRoles::BASE_USER->value;
        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     * @return User
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    #[ORM\PostPersist]
    public function postPersist(): void
    {
        $this->lastPasswordChange = new DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
    }

    #[ORM\PostUpdate]
    public function postUpdate(): void
    {
    }

    public function getReadableRoles(): array
    {
        $roles = $this->getRoles();
        $roles = array_diff($roles, [UserRoles::BASE_USER->value]);
        return array_map(fn($role) => new TranslatableMessage('components.user.user_role', ['role' => $role]), $roles);
    }

    public function hasRole(string|UserRoles $role): bool
    {
        if ($role instanceof UserRoles) {
            $role = $role->value;
        }

        return in_array($role, $this->getRoles());
    }

    public function getLastPasswordChange(): DateTimeImmutable
    {
        return $this->lastPasswordChange;
    }

    public function setLastPasswordChange(DateTimeImmutable $lastPasswordChange): self
    {
        $this->lastPasswordChange = $lastPasswordChange;
        return $this;
    }

    public function isPasswordChangeRequired(): bool
    {
        return $this->lastPasswordChange->modify('+' . self::PASSWORD_CHANGE_INTERVAL) < new DateTimeImmutable();
    }

    public function forcePasswordChange(): void
    {
        $this->lastPasswordChange = (new DateTimeImmutable())->modify('-' . self::PASSWORD_CHANGE_INTERVAL);
    }
}
