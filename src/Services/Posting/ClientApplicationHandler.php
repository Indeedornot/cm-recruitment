<?php

namespace App\Services\Posting;

use App\Repository\ClientApplicationRepository;
use App\Repository\GlobalConfigRepository;
use App\Repository\PostingRepository;
use App\Repository\PostingTextRepository;

class ClientApplicationHandler
{
    public function __construct(
        private readonly PostingRepository $postingRepository,
        private readonly PostingTextRepository $postingTextRepository,
        private readonly GlobalConfigRepository $globalConfigRepository,
        private readonly ClientApplicationRepository $applicationRepository
    ) {
    }

    public function getSortedApplications(int $postingId): array
    {
        $applications = $this->postingRepository->find($postingId)->getApplications();
        foreach ($applications as $application) {
            $bonusCriteria = $application->getValueByKey('bonus_criteria');
        }
    }
}
