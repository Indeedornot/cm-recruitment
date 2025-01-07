<?php

namespace App\Form;

use App\Entity\SubPosting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubPostingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'components.sub_posting.form.title',
                'required' => true,
                'row_attr' => [
                    'class' => 'col-12 col-md-4 d-inline-block pe-2 mb-3',
                ],
            ])
            ->add('time', TextType::class, [
                'label' => 'components.sub_posting.form.time',
                'required' => true,
                'row_attr' => [
                    'class' => 'col-12 col-md-4 d-inline-block pe-2 mb-3',
                ],
            ])
            ->add('personLimit', IntegerType::class, [
                'label' => 'components.sub_posting.form.person_limit',
                'required' => true,
                'attr' => [
                    'min' => 1,
                ],
                'row_attr' => [
                    'class' => 'col-12 col-md-4 d-inline-block mb-3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SubPosting::class,
        ]);
    }
}
