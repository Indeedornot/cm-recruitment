<?php

namespace App\EventListener;

use App\Entity\GlobalConfig;
use App\Repository\PostingRepository;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: GlobalConfig::class)]
class GlobalConfigListener
{
    public function __construct(private readonly PostingRepository $postingRepository)
    {
    }

    public function preUpdate(GlobalConfig $globalConfig, PreUpdateEventArgs $event): void
    {
        if ($globalConfig->getKey() === 'closing_date' && array_key_exists('value', $event->getEntityChangeSet())) {
            $this->postingRepository->updateActiveClosingDates(new DateTimeImmutable($globalConfig->getValue()));
        }
    }
}
