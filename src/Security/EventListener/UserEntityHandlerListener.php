<?php

namespace App\Security\EventListener;

use App\Security\Entity\User;
use App\Security\Event\UserEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Handles any operations needed to be done to the User object pre/post persist/update.
 * Which require injected services or other dependencies.
 */
#[AsEventListener(event: UserEvent::PRE_USER_CREATED, method: 'onPreUserCreated')]
class UserEntityHandlerListener
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function onPreUserCreated(User $user): void
    {
        if ($user->getPlainPassword() && empty($user->getPassword())) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPlainPassword()));
        }
    }
}
