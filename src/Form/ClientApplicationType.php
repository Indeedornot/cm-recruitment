<?php

namespace App\Form;

use App\Entity\ClientApplication;
use App\Entity\QuestionnaireAnswer;
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
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $application = $builder->getData();
        $posting = $application->getPosting();

        foreach ($posting->getQuestionnaire()->getQuestions() as $question) {
            $builder->add('answer_' . $question->getId(), TextType::class, [
                'label' => $question->getLabel(),
                'required' => !$question->getIsNullable(),
                'constraints' => $question->getConstraints(),
                'mapped' => false,
            ]);
        }

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $application = $event->getData();
            $form = $event->getForm();

            foreach ($application->getPosting()->getQuestionnaire()->getQuestions() as $question) {
                $answer = new QuestionnaireAnswer();
                $answer->setQuestion($question);
                $answer->setAnswer($form->get('answer_' . $question->getId())->getData());
                $application->addAnswer($answer);
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
