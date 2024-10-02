<?php

namespace App\Security\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
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
}
