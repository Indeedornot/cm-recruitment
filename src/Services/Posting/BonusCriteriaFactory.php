<?php

namespace App\Services\Posting;

use App\Contract\Patterns\Factory\FactoryResolver;
use App\Contract\Patterns\Factory\ParametrizedFactory;
use App\Entity\ClientApplication;
use App\Entity\QuestionnaireAnswer;
use App\Repository\BonusCriteriaRepository;
use App\Repository\ClientApplicationRepository;
use App\Repository\GlobalConfigRepository;
use Webmozart\Assert\Assert;

/**
 * @extends ParametrizedFactory<array|false>
 */
class BonusCriteriaFactory implements ParametrizedFactory
{
    public function __construct(
        private readonly GlobalConfigRepository $globalConfigRepository,
        private readonly ClientApplicationRepository $applicationRepository,
        private readonly BonusCriteriaRepository $bonusCriteriaRepository
    ) {
    }

    public function __invoke(array $params)
    {
        Assert::keyExists($params, 'postingId');
        Assert::keyExists($params, 'question_key');
        Assert::keyExists($params, 'applicationId');

        Assert::positiveInteger($params['postingId']);
        Assert::nullOrPositiveInteger($params['applicationId']);
        Assert::eq($params['question_key'], 'bonus_criteria');

        $postingId = $params['postingId'];
        $applicationId = $params['applicationId'];
        $questionKey = $params['question_key'];

        $applicationPhase = null;
        if (is_integer($applicationId)) {
            /** @var ClientApplication|null $application */
            $application = $this->applicationRepository->find($applicationId);
            Assert::notNull($application);
            $applicationPhase = $application->getDataByKey('application_phase');
        }

        if (empty($applicationPhase)) {
            $applicationPhase = $this->globalConfigRepository->getValue('application_phase');
        }

        Assert::stringNotEmpty($applicationPhase);
        Assert::inArray($applicationPhase, [
            'continuation',
            'first_phase',
            'second_phase',
        ]);

        $bonusCriteria = $this->bonusCriteriaRepository->findByPhase($applicationPhase);
        $choices = array_merge(array_map(function ($criteria) {
            return [$criteria->getLabel() => $criteria->getKey()];
        }, $bonusCriteria));

        return empty($choices) ? false : $choices;
    }
}
