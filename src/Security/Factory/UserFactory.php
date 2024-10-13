<?php

namespace App\Security\Factory;

use App\Contract\Exception\InvalidFieldException;
use App\Entity\ClientApplication;
use App\Security\Entity\Admin;
use App\Security\Entity\Client;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use InvalidArgumentException;
use LogicException;

class UserFactory
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function createEmptyAdmin(): Admin
    {
        return new Admin();
    }

    public function createEmptyClient(): Client
    {
        return new Client();
    }

    /**
     * @throws LogicException
     * @throws InvalidFieldException If email is already taken
    }
}
