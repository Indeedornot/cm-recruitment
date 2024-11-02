<?php

namespace App\Security\Event;

use Doctrine\ORM\Event\PostPersistEventArgs;

/**
 * @extends  UserEvent<PostPersistEventArgs>
 */
class PostUserCreatedEvent extends UserEvent
{
    public function getEventName(): string
    {
        return self::POST_USER_CREATED;
    }
}
