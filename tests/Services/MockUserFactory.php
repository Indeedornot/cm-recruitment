<?php

namespace App\Tests\Services;

use App\Security\Entity\Admin;
use App\Security\Entity\Client;
use App\Security\Factory\UserFactory;

class MockUserFactory
{
    private UserFactory $userFactory;

    function __construct()
    {
        $this->userFactory = new UserFactory(
            new MockEventDispatcher(),
            new MockPasswordHasher(),
        );
    }

    public function createAdmin(): Admin
    {
        $admin = $this->userFactory->createAdmin()
            ->setPlainPassword('password')
            ->setEmail('test@test.com')
            ->setName('Test Admin');

        $admin->setCreatedBy($admin);
        return $admin;
    }

    public function createClient(): Client
    {
        $client = $this->userFactory->createClient()
            ->setPlainPassword('password')
            ->setEmail('test@test.com')
            ->setName('Test Client');

        return $client;
    }
}
