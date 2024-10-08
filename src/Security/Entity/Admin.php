<?php

namespace App\Security\Entity;

use App\Entity\Trait\CreatedByAdmin;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Admin extends User
{
    use CreatedByAdmin;

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
}
