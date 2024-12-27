<?php

namespace App\Form;

use App\Entity\ClientApplication;
use App\Entity\Questionnaire;
use App\Entity\QuestionnaireAnswer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplyQuestionnaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!$options['posting'] || !$options['application'] || $builder->getData()) {
            return;
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            /** @var Questionnaire $questionnaire */
            $questionnaire = $event->getData();
            $form = $event->getForm();
            if (!$questionnaire) {
                return;
            }

            foreach ($questionnaire->getQuestions() as $index => $question) {
                $form->add('answer_' . $question->getId(), TextType::class, [
                    'label' => $question->getLabel(),
                    'mapped' => false,
                    'required' => $question->getIsNullable(),
                ]);
            }
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options) {
            /** @var Questionnaire $questionnaire */
            $questionnaire = $event->getData();
            /** @var ClientApplication $application */
            $application = $options['application'];
            $form = $event->getForm();

            foreach ($questionnaire->getQuestions() as $index => $question) {
                $answer = new QuestionnaireAnswer();
                $answer->setQuestion($question);
                $answer->setAnswer($form->get('answer_' . $question->getId())->getData());
                $questionnaire->addAnswer($answer);
                $application->addAnswer($answer);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Questionnaire::class,
            'application' => null,
        ]);
    }
}
