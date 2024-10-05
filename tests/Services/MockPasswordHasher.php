<?php

namespace App\Tests\Services;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class MockPasswordHasher implements UserPasswordHasherInterface
{

    public function hashPassword(
        PasswordAuthenticatedUserInterface $user,
        #[\SensitiveParameter] string $plainPassword
    ): string {
        return $plainPassword;
    }

    public function isPasswordValid(
        PasswordAuthenticatedUserInterface $user,
        #[\SensitiveParameter] string $plainPassword
    ): bool {
        return $user->getPassword() === $plainPassword;
    }

    public function needsRehash(PasswordAuthenticatedUserInterface $user): bool
    {
        return false;
    }
}
