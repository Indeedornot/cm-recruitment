<?php

namespace App\Security\Factory;

use App\Contract\Exception\InvalidFieldException;
use App\Entity\ClientApplication;
use App\Security\Entity\Admin;
use App\Security\Entity\Client;
use App\Security\Entity\User;
use App\Security\Services\UserEmailService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use InvalidArgumentException;
use LogicException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher,
        private UserEmailService $emailService
    ) {
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
            $email = $application->getValueByKey('email');
            $name = array_filter(array_map(fn($key) => $application->getValueByKey($key), ['first_name', 'last_name']));
            if (count($name) > 0) {
                $name = implode(' ', $name);
            } else {
                $name = $email;
            }

            $password = $this->generatePassword();
            $client
                ->setEmail($email)
                ->setPlainPassword($password)
                ->setName($name);

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

    public function hashPassword(User $user, string $password): string
    {
        return $this->passwordHasher->hashPassword($user, $password);
    }

    public function checkPassword(User $user, string $password): bool
    {
        return $this->passwordHasher->isPasswordValid($user, $password);
    }

    public function resetPassword(User $user): void
    {
        $pswd = $this->generatePassword();
        $user
            ->setPlainPassword($pswd)
            ->forcePasswordChange();
        $this->em->flush();

        $this->emailService->sendToUserPasswordResetMail($user);
    }
}
