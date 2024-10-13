<?php

namespace App\Security\Form;

use App\Form\Exception;
use App\Security\Entity\Admin;
use App\Security\Entity\User;
use App\Security\Factory\UserFactory;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{EmailType, PasswordType, RepeatedType, TextType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    private int $minPasswordLength = 12;

    public function __construct(
        private Security $security,
        private UserFactory $userFactory
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('name', TextType::class);

        if ($options['mode'] !== 'edit') {
            if ($options['require_password'] ?? true) {
                $builder->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'first_options' => [
                        'label' => 'Password',
                        'constraints' => [
                            new Assert\NotBlank(),
                            new Assert\Length(['min' => $this->minPasswordLength])
                        ],
                    ],
                    'second_options' => ['label' => 'Confirm Password']
                ]);
            } else {
                $pswd = $this->userFactory->generatePassword($this->minPasswordLength);
                $builder->getData()
                    ->setPassword($pswd)
                    ->setPlainPassword($pswd);
            }
        }
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'require_password' => true,
            'mode' => 'create',
            'createdBy' => $this->security->getUser()
        ]);
    }
}
