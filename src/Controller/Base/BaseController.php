<?php

namespace App\Controller\Base;

use App\Security\Entity\Admin;
use App\Security\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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

    public function isLoggedIn(): bool
    {
        return $this->getUser() !== null;
    }

    public function getAdmin(): ?Admin
    {
        return $this->getUserSubClass(Admin::class);
    }

    public function setErrorHandler(ErrorHandlerType $handler, array $options = []): void
    {
        $this->container->get('request_stack')->getSession()->set(
            'error_handler',
            ['handler' => $handler->value, 'options' => $options]
        );
    }
}
