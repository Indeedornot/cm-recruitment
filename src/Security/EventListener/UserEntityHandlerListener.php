<?php

namespace App\Security\EventListener;

use App\Security\Event\PreUserChangedEvent;
use App\Security\Event\UserEvent;
use App\Security\Factory\UserFactory;
use DateTimeImmutable;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * Handles any operations needed to be done to the User object pre/post persist/update.
 * Which require injected services or other dependencies.
 */
#[AsEventListener(event: UserEvent::PRE_USER_CREATED, method: 'onPreUserCreated')]
#[AsEventListener(event: UserEvent::PRE_USER_CHANGED, method: 'onPreUserChanged')]
class UserEntityHandlerListener
{
    public function __construct(private readonly UserFactory $userFactory)
    {
    }

    public function onPreUserChanged(PreUserChangedEvent $event): void
    {
        if ($event->getArgs()->hasChangedField('password')) {
            $event->getUser()->setLastPasswordChange(new DateTimeImmutable());
        }
    }

    public function onPreUserCreated(UserEvent $event): void
    {
        $user = $event->getUser();
        if ($user->getPlainPassword() && empty($user->getPassword())) {
            $user->setPassword($this->userFactory->hashPassword($user, $user->getPlainPassword()));
        }

        if (empty($user->getLastPasswordChange())) {
            $user->setLastPasswordChange(new DateTimeImmutable());
        }
    }
}
