<?php

namespace App\Form;

use App\Contract\PhoneNumber\Form\Type\PhoneNumberType;
use App\Entity\Posting;
use App\Entity\Questionnaire;
use App\Security\Entity\Admin;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionnaireType extends AbstractType
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Imię'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nazwisko'
            ])
            ->add('age', NumberType::class, [
                'label' => 'Wiek'
            ])
            ->add('pesel', TextType::class, [
                'label' => 'Pesel'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('phone', PhoneNumberType::class, [
                'label' => 'Numer telefonu'
            ])
            ->add('houseNo', TextType::class, [
                'label' => 'Numer domu'
            ])
            ->add('street', TextType::class, [
                'label' => 'Ulica',
                'required' => false
            ])
            ->add('city', TextType::class, [
                'label' => 'Miasto'
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Kod Pocztowy'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Questionnaire::class,
            'createdBy' => $this->security->getUser()
        ]);
    }
}
