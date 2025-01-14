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

        $phases = [
        ];
        foreach ($applications as $application) {
            $points = 0;
            $bonusCriteria = $application->getValueByKey('bonus_criteria');
            if ($bonusCriteria) {
                $values = $this->bonusCriteriaRepository->getValuesByKeys($applicationPhase, $bonusCriteria);
                $points += array_sum($values);
            }

            $phase = $application->getDataByKey('application_phase');
            $phases[$phase][] = [
                'application' => $application,
                'points' => $points
            ];
        }

        foreach ($phases as $phase => $data) {
            usort($phases[$phase], function ($a, $b) {
                return $b['points'] <=> $a['points'];
            });
        }

        $sorted = array_merge(...array_values($phases));
        $grouppedBySchedule = [];
        foreach ($sorted as $item) {
            $schedule = $item['application']->getSchedule()?->getId();
            if (!isset($grouppedBySchedule[$schedule])) {
                $grouppedBySchedule[$schedule] = [];
            }
            $grouppedBySchedule[$schedule][] = $item;
        }

        return $grouppedBySchedule;
    }
}
