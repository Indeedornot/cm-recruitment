<?php

namespace App\Services\Posting;

use App\Repository\BonusCriteriaRepository;
use App\Repository\ClientApplicationRepository;
use App\Repository\GlobalConfigRepository;
use App\Repository\PostingRepository;
use App\Repository\PostingTextRepository;

class ClientApplicationHandler
{
    public function __construct(
        private readonly PostingRepository $postingRepository,
        private readonly GlobalConfigRepository $globalConfigRepository,
        private readonly BonusCriteriaRepository $bonusCriteriaRepository
    ) {
    }

    public function getSortedApplications(int $postingId): array
    {
        $applications = $this->postingRepository->find($postingId)->getApplications();
        $applicationPhase = $this->globalConfigRepository->getValue('application_phase');
        
        $sorted = [];
        foreach ($applications as $application) {
            $points = 0;
            $bonusCriteria = $application->getValueByKey('bonus_criteria');
            if ($bonusCriteria) {
                $values = $this->bonusCriteriaRepository->getValuesByKeys($applicationPhase, $bonusCriteria);
                $points += array_sum($values);
            }

            $sorted[] = [
                'application' => $application,
                'points' => $points
            ];
        }

        usort($sorted, fn($a, $b) => $b['points'] <=> $a['points']);
        return $sorted;
    }
}
