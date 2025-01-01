<?php

namespace App\EventListener;

use App\Entity\ClientApplication;
use App\Repository\GlobalConfigRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'setAdditionalData', entity: ClientApplication::class)]
class ClientApplicationListener
{
    public function __construct(private readonly GlobalConfigRepository $configRepository)
    {
    }

    public function setAdditionalData(ClientApplication $application, mixed $event): void
    {
        $key = 'application_phase';
        if (!empty($application->getDataByKey($key))) {
            return;
        }

        $phase = $this->configRepository->getValue($key);
        if (empty($phase)) {
            throw new \LogicException('Application phase is not set');
        }
        $application->setDataByKey($key, $this->configRepository->getValue($key));
    }
}
