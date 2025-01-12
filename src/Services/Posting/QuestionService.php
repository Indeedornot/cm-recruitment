<?php

namespace App\Services\Posting;

use App\Contract\Patterns\Factory\FactoryResolver;
use App\Contract\Patterns\Factory\InvokableFactory;
use App\Contract\Patterns\Factory\ParametrizedFactory;
use App\Entity\ClientApplication;
use App\Entity\Posting;
use App\Entity\Question;
use App\Entity\QuestionnaireAnswer;
use App\Repository\BonusCriteriaRepository;
use App\Repository\GlobalConfigRepository;
use App\Repository\PostingRepository;
use App\Repository\PostingTextRepository;
use App\Repository\QuestionnaireAnswerRepository;
use App\Security\Services\ExtendedSecurity;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;

class QuestionService
{
    public function __construct(
        private readonly FactoryResolver $factoryResolver,
        private readonly ExtendedSecurity $security,
        private readonly QuestionnaireAnswerRepository $answerRepository,
        private readonly ValidatorInterface $validator,
        private readonly BonusCriteriaRepository $bonusCriteriaRepository,
    ) {
    }

    /** @param Collection<int, Question> $questions */
    public function addQuestions(FormBuilderInterface $builder, Collection $questions): void
    {
        $application = $builder->getData();
        Assert::isInstanceOf($application, ClientApplication::class);

        foreach ($questions as $question) {
            $formOptions = $this->getFormOptions($question, $application);
            if ($formOptions === false) {
                continue;
            }

            $builder->add('answer_' . $question->getId(), $question->getFormType(), array_merge([
                'label' => $question->getLabel(),
                'required' => !$question->getIsNullable(),
                'constraints' => $question->getConstraints(),
                'mapped' => false,
            ], $formOptions));
        }
    }

    public function fillPreviousAnswers(
        FormBuilderInterface $builder,
        Collection $questions,
        Collection $answers
    ): void {
        if (!$answers->isEmpty()) {
            foreach ($answers as $answer) {
                $builder->get('answer_' . $answer->getQuestion()->getId())->setData($answer->getAnswer());
            }
        } elseif ($this->security->isLoggedIn()) {
            $previousAnswers = $this->answerRepository->getPreviousAnswers(
                $questions->map(fn($question) => $question->getId())->toArray(),
                $this->security->getUser()->getId()
            );
            foreach ($previousAnswers as $previousAnswer) {
                $builder->get('answer_' . $previousAnswer->getQuestion()->getId())->setData($previousAnswer->getAnswer());
            }
        }
    }

    public function addAnswerSubmitHandler(FormBuilderInterface $builder)
    {
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var ClientApplication $application */
            $application = $event->getData();
            $form = $event->getForm();

            foreach ($application->getPosting()->getQuestionnaire()->getQuestions() as $question) {
                if (!$form->has('answer_' . $question->getId())) {
                    continue;
                }
                $answer = new QuestionnaireAnswer();
                $answer->setQuestion($question);
                $answer->setAnswer($form->get('answer_' . $question->getId())->getData());
                $answer->setApplication($application);

                // Validate the answer
                $errors = $this->validator->validate($answer);
                if (count($errors) > 0) {
                    foreach ($errors as $error) {
                        $form->get('answer_' . $question->getId())->addError(new FormError($error->getMessage()));
                    }
                }

                $previousAnswer = $this->answerRepository->findOneBy([
                    'question' => $question,
                    'application' => $application,
                ]);

                if ($previousAnswer) {
                    $previousAnswer->setAnswer($answer->getAnswer());
                } else {
                    $application->addAnswer($answer);
                }
            }
        });
    }

    public function getFormOptions(Question $question, ClientApplication $application): array|false
    {
        $posting = $application->getPosting();

        $formOptions = $question->getFormOptions();
        if (array_key_exists('choice_factory', $formOptions)) {
            $choiceFactory = $formOptions['choice_factory'];
            $factory = $choiceFactory['factory'];
            $params = array_merge([
                'postingId' => $posting->getId(),
                'applicationId' => $application->getId(),
                'question_key' => $question->getQuestionKey(),
            ], $choiceFactory['params']);
            $choices = $this->factoryResolver->resolveFactory($factory)($params);
            if ($choices === false) {
                return false;
            }
            $formOptions['choices'] = $choices;
            unset($formOptions['choice_factory']);
        }
        return $formOptions;
    }

    public function getAnswerLabel(QuestionnaireAnswer $answer): array|string
    {
        $question = $answer->getQuestion();
        $answer = $answer->getAnswer();
        if (is_array($answer) && $question->getQuestionKey() === 'bonus_criteria') {
            return $this->bonusCriteriaRepository->findLabelsByKeys($answer);
        }
        return (string)$answer;
    }
}
