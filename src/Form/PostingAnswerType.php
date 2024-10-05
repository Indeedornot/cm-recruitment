<?php

namespace App\Form;

use App\Entity\Posting;
use App\Entity\PostingAnswer;
use App\Entity\PostingQuestion;
use App\Security\Entity\Client;
use Shapecode\Bundle\HiddenEntityTypeBundle\Form\Type\HiddenEntityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostingAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('answer')
            ->add('question', HiddenEntityType::class, [
                'class' => PostingQuestion::class,
                'choice_label' => 'id',
            ])
            ->add('posting', HiddenEntityType::class, [
                'class' => Posting::class,
            ])
            ->add('user', HiddenEntityType::class, [
                'class' => Client::class,
            ]);

        /** @var PostingAnswer $answer */
        $answer = $builder->getData();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PostingAnswer::class,
        ]);
    }
}
