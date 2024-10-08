<?php

namespace App\Form;

use App\Entity\ClientApplication;
use App\Entity\PostingQuestion;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientApplicationType extends AbstractType
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var PostingQuestion[] $questions */
        $questions = $options['questions'];

        $builder
            ->add('questionnaire', QuestionnaireType::class)
            ->add('answers', CollectionType::class, [
                'entry_type' => PostingAnswerType::class,
                'entry_options' => [
                    'questions' => $questions
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientApplication::class,
            'client' => $this->security->getUser()
        ]);
    }
}
