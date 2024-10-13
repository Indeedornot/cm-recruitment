<?php

namespace App\Form;

use App\Entity\PostingAnswer;
use App\Entity\PostingQuestion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostingAnswerType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['question'] instanceof PostingQuestion) {
            $builder->setData(new PostingAnswer());
        }

        $builder
            ->add('answer', TextType::class, [
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PostingAnswer::class,
            'question' => null,
            'empty_data' => function (FormInterface $form) {
                return new PostingAnswer();
            }
        ]);
    }
}
