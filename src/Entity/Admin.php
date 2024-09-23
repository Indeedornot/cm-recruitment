<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
#[ORM\Table(name: '`admin`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\HasLifecycleCallbacks]
class Admin extends User
{
    #[ORM\OneToOne(targetEntity: Admin::class)]
    private Admin $createdBy;

    /**
     * @return list<string>
     * @see UserInterface
     *
     */
    public function getRoles(): array
    {
        $roles = parent::getRoles();
        $roles[] = UserRoles::ADMIN->value;
        return array_unique($roles);
    }

    public function getCreatedBy(): Admin
    {
        return $this->createdBy;
    }

    public function setCreatedBy(Admin $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
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
