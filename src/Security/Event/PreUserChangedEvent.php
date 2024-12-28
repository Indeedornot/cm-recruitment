<?php

namespace App\Security\Event;

use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * @extends  UserEvent<PreUpdateEventArgs>
 */
class PreUserChangedEvent extends UserEvent
{
    public function getEventName(): string
    {
        return self::PRE_USER_CHANGED;
    }
}
