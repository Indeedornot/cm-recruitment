<?php

namespace App\Security\Event;

use Doctrine\ORM\Event\PrePersistEventArgs;

/**
 * @extends  UserEvent<PrePersistEventArgs>
 */
class PreUserCreatedEvent extends UserEvent
{
    public function getEventName(): string
    {
        return self::PRE_USER_CREATED;
    }
}
