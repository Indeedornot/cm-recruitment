<?php

namespace App\Services\Posting;

use App\Contract\Patterns\Factory\ParametrizedFactory;
use App\Repository\GlobalConfigRepository;
use Webmozart\Assert\Assert;

/**
 * Application Phase dependant questions
 */
class APDepFactory implements ParametrizedFactory
{
    public function __construct(public GlobalConfigRepository $globalConfigRepository)
    {
    }

    public function __invoke(array $params)
    {
        Assert::keyExists($params, 'applicationPhase');
        /** @var string[] $applicationPhases */
        $applicationPhases = $params['applicationPhase'];
        Assert::allInArray($applicationPhases, [
            'continuation',
            'first_phase',
            'second_phase',
        ]);

        $currentPhase = $this->globalConfigRepository->getValue('application_phase');
        if (in_array($currentPhase, $applicationPhases)) {
            return [];
        } else {
            return false;
        }
    }
}
