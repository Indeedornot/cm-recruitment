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
        if (empty($builder->getData())) {
            $builder->setData(new Questionnaire());
        }

        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('age', NumberType::class)
            ->add('pesel', TextType::class)
            ->add('email', EmailType::class)
            ->add('phone', PhoneNumberType::class)
            ->add('houseNo', TextType::class)
            ->add('street', TextType::class)
            ->add('city', TextType::class)
            ->add('postalCode', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Questionnaire::class,
            'createdBy' => $this->security->getUser()
        ]);
    }
}
