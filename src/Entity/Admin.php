<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
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
}
