<?php

namespace App\Security\Factory;

use App\Security\Entity\Admin;
use App\Security\Entity\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function createAdmin(): Admin
    {
        return new Admin($this->eventDispatcher, $this->passwordHasher);
    }

    public function createClient(): Client
    {
        return new Client($this->eventDispatcher, $this->passwordHasher);
    }
}
