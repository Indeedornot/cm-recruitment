<?php

namespace App\Form;

use App\Entity\ClientApplication;
use App\Repository\PostingQuestionRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientApplicationType extends AbstractType
{
    public function __construct(
        private Security $security,
        private PostingQuestionRepository $postingQuestionRepository
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('questionnaire', QuestionnaireType::class, [
                'label' => 'Podstawowy Kwestionariusz'
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (PreSetDataEvent $event) {
            $form = $event->getForm();
            $clientApplication = $event->getData();

            if (empty($clientApplication)) {
                return;
            }

            $posting = $clientApplication->getPosting();
            $questions = $posting->getQuestions();

            foreach ($questions as $index => $question) {
                $form->add('answers_' . $index, PostingAnswerType::class, [
                    'mapped' => false,
                    'allow_extra_fields' => true,
                    'question' => $question,
                    'label' => $question->getTitle(),
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientApplication::class,
            'client' => $this->security->getUser(),
            'posting' => null,
        ]);
    }
}
