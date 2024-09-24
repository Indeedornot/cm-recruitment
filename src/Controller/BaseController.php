<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class BaseController extends AbstractController
{
    public function getClient(): ?Client
    {
        return $this->getUserSubClass(Client::class);
    }

    /**
     * @template T of class-string
     * @param T $subClass
     * @return T|null
     */
    private function getUserSubClass(string $subClass)
    {
        $user = $this->getUser();
        if (empty($user)) {
            return null;
        }

        if (!(is_a($user, $subClass))) {
            throw new \LogicException(sprintf('User is not a %s', $subClass));
        }

        return $user;
    }

    public function getAdmin(): ?Admin
    {
        return $this->getUserSubClass(Admin::class);
    }
}
