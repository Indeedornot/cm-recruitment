<?php

namespace App\Security\Event;

use App\Security\Entity\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @template T of LifecycleEventArgs
 */
abstract class UserEvent extends Event
{
    public const string PRE_USER_CHANGED = 'pre.user.changed';
    public const string POST_USER_CHANGED = 'post.user.changed';

    public const string PRE_USER_CREATED = 'pre.user.created';
    public const string POST_USER_CREATED = 'post.user.created';

    /**
     * @param User $user
     * @param T|null $args
     */
    public function __construct(private readonly User $user, private readonly mixed $args = null)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return T|null
     */
    public function getArgs()
    {
        return $this->args;
    }

    abstract public function getEventName();
}
