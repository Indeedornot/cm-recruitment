<?php

namespace App\Security\Event;

use App\Security\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserEvent extends Event
{
    public const string PRE_USER_CHANGED = 'pre.user.changed';
    public const string POST_USER_CHANGED = 'post.user.changed';

    public const string PRE_USER_CREATED = 'pre.user.created';
    public const string POST_USER_CREATED = 'post.user.created';

    public function __construct(private readonly User $user)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
