<?php

namespace App\Form;

use App\Entity\ClientApplication;
use App\Entity\QuestionnaireAnswer;
use App\Repository\QuestionnaireAnswerRepository;
use LogicException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\ApplyQuestionnaireType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ClientApplicationType extends AbstractType
{
    public function __construct(
        private Security $security,
        private QuestionnaireAnswerRepository $answerRepository
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $application = $builder->getData();
        $posting = $application->getPosting();
        $questions = $posting->getQuestionnaire()->getQuestions();

        foreach ($questions as $question) {
            $builder->add('answer_' . $question->getId(), TextType::class, [
                'label' => $question->getLabel(),
                'required' => !$question->getIsNullable(),
                'constraints' => $question->getConstraints(),
                'mapped' => false,
            ]);
        }

        if (!$application instanceof ClientApplication) {
            throw new LogicException('ClientApplicationType can only be used with ClientApplication objects');
        }

        $answers = $application->getAnswers();
        if (!$answers->isEmpty()) {
            foreach ($application->getAnswers() as $answer) {
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

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var ClientApplication $application */
            $application = $event->getData();
            $form = $event->getForm();

            foreach ($application->getPosting()->getQuestionnaire()->getQuestions() as $question) {
                $answer = new QuestionnaireAnswer();
                $answer->setQuestion($question);
                $answer->setAnswer($form->get('answer_' . $question->getId())->getData());
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientApplication::class,
            'client' => $this->security->getUser(),
        ]);
    }
}
