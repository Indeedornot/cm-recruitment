<?php

namespace App\Security\Event;

use Doctrine\ORM\Event\PostUpdateEventArgs;

/**
 * @extends  UserEvent<PostUpdateEventArgs>
 */
class PostUserChangedEvent extends UserEvent
{
    public function getEventName(): string
    {
        return self::POST_USER_CHANGED;
    }
}
