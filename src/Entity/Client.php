<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\Table(name: '`client`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\HasLifecycleCallbacks]
class Client extends User
{
    /**
     * @return list<string>
     * @see UserInterface
     *
     */
    public function getRoles(): array
    {
        $roles = parent::getRoles();
        $roles[] = UserRoles::CLIENT->value;
        return array_unique($roles);
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        parent::prePersist();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        parent::preUpdate();
    }
}
