<?php

namespace App\Services\Posting;

use App\Contract\Patterns\Factory\FactoryResolver;
use App\Entity\ClientApplication;
use App\Entity\CopyText;
use App\Entity\Question;
use App\Repository\CopyTextRepository;

class CopyTextService
{
    public function __construct(
        private readonly CopyTextRepository $copyTextRepository,
        public readonly FactoryResolver $factoryResolver
    ) {
    }

    public function getFormOptions(CopyText $copyText): array|false
    {
        $formOptions = $copyText->getFormOptions();
        if (array_key_exists('choice_factory', $formOptions)) {
            $choiceFactory = $formOptions['choice_factory'];
            $factory = $choiceFactory['factory'];
//            $params = array_merge([
//                'postingId' => $posting->getId(),
//                'applicationId' => $application->getId(),
//                'question_key' => $question->getQuestionKey(),
//            ], $choiceFactory['params']);
            $choices = $this->factoryResolver->resolveFactory($factory)([]);
            if ($choices === false) {
                return false;
            }
            $formOptions['choices'] = $choices;
            unset($formOptions['choice_factory']);
        }
        return $formOptions;
    }
}
