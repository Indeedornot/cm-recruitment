<?php

namespace App\Tests\Services;

use App\Security\Entity\Admin;
use App\Security\Entity\Client;
use App\Security\Factory\UserFactory;

class MockUserFactory
{
    public function createAdmin(): Admin
    {
        $admin = (new Admin())
            ->setPlainPassword('password')
            ->setEmail('test@test.com')
            ->setName('Test Admin');

        $admin->setCreatedBy($admin);
        return $admin;
    }

    public function createClient(): Client
    {
        $client = (new Client())
            ->setPlainPassword('password')
            ->setEmail('test@test.com')
            ->setName('Test Client');

        return $client;
    }
}
