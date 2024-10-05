<?php

namespace App\Security\Entity;

use App\Security\Event\PostUserChangedEvent;
use App\Security\Event\PreUserChangedEvent;
use App\Security\Event\UserEvent;
use App\Security\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;
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
    protected DateTimeImmutable $createdAt;
    #[ORM\Column]
    protected DateTimeImmutable $updatedAt;

    #[ORM\Column(options: ['default' => true])]
    protected bool $isEnabled = true;

    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

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

    public function setAndHashPassword(string $password): static
    {
        $this->password = $this->passwordHasher->hashPassword($this, $password);

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
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->eventDispatcher->dispatch(new UserEvent($this), UserEvent::PRE_USER_CREATED);
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
        $this->eventDispatcher->dispatch(new UserEvent($this), UserEvent::POST_USER_CREATED);
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->eventDispatcher->dispatch(new UserEvent($this), UserEvent::PRE_USER_CHANGED);
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\PostUpdate]
    public function postUpdate(): void
    {
        $this->eventDispatcher->dispatch(new UserEvent($this), UserEvent::POST_USER_CHANGED);
    }

    public function getReadableRoles(): array
    {
        $roles = $this->getRoles();
        return array_map(fn($role) => UserRoles::from($role)->getLabel(), $roles);
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): void
    {
        $this->isEnabled = $isEnabled;
    }
}
