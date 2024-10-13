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
     */
    public function createTemporaryClient(ClientApplication $application): Client
    {
        try {
            $client = new Client();
            $client
                ->setEmail($application->getQuestionnaire()->getEmail())
                ->setPassword($this->generatePassword())
                ->setName($application->getQuestionnaire()->getEmail());

            $this->em->persist($client);
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new InvalidFieldException('email', $e);
        } catch (Exception $e) {
            throw new LogicException('Failed to create temporary client', 0, $e);
        }

        return $client;
    }

    /**
     * Generate a random string, using a cryptographically secure
     * pseudorandom number generator (random_int)
     *
     * @param int $length How many characters do we want?
     * @param string $keyspace A string of all possible characters to select from
     * @return string
     * @throws InvalidArgumentException If keyspace is too small
     */
    public function generatePassword(
        int $length = 12,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        if ($max < 1) {
            throw new InvalidArgumentException('$keyspace must be at least two characters long');
        }
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }
}
