<?php

namespace App\Security\EventListener;

use App\Security\Entity\User;
use App\Security\Event\UserEvent;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: User::class)]
#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: User::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: User::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: User::class)]
class UserEventProxy
{
    function __construct(private readonly EventDispatcherInterface $dispatcher)
    {
    }

    public function postPersist(User $user, PostPersistEventArgs $event): void
    {
        $_ = $this->dispatcher->dispatch(new UserEvent($user), UserEvent::POST_USER_CREATED);
    }

    public function prePersist(User $user, PrePersistEventArgs $event): void
    {
        $_ = $this->dispatcher->dispatch(new UserEvent($user), UserEvent::PRE_USER_CREATED);
    }

    public function postUpdate(User $user, PostUpdateEventArgs $event): void
    {
        $_ = $this->dispatcher->dispatch(new UserEvent($user), UserEvent::POST_USER_CHANGED);
    }

    public function preUpdate(User $user, PreUpdateEventArgs $event): void
    {
        $_ = $this->dispatcher->dispatch(new UserEvent($user), UserEvent::PRE_USER_CHANGED);
    }
}
