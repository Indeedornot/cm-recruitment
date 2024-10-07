<?php

namespace App\Form;

use App\Entity\Posting;
use App\Security\Entity\Admin;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostingType extends AbstractType
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (empty($builder->getData())) {
            $builder->setData(new Posting());
        }

        $builder
            ->add('title', TextType::class)
            ->add('description')
            ->add('assignedTo', EntityType::class, [
                'class' => Admin::class,
                'choice_label' => 'name',
                'placeholder' => 'Select an admin',
                'required' => false,
            ])
            ->add('questions', CollectionType::class, [
                'entry_type' => PostingQuestionType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posting::class,
        ]);
        $resolver->setDefault('createdBy', $this->security->getUser());
    }
}
