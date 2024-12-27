<?php

namespace App\Security\Form;

use App\Form\Exception;
use App\Security\Entity\Admin;
use App\Security\Entity\User;
use App\Security\Factory\UserFactory;
use App\Security\Services\ExtendedSecurity;
use LogicException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{ButtonType,
    EmailType,
    PasswordType,
    RepeatedType,
    SubmitType,
    TextType
};
use Symfony\Component\Form\Button;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\Form\SubmitButtonTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserType extends AbstractType
{
    private int $minPasswordLength = 12;

    public function __construct(
        private ExtendedSecurity $security,
        private UserFactory $userFactory
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var UserFormMode $mode */
        $mode = $options['mode'];

        $options = in_array($mode,
            [UserFormMode::EDIT, UserFormMode::PASSWORD_CHANGE]
        ) ? $this->getDisabledOptions() : [];
        $builder
            ->add('email', EmailType::class)
            ->add('name', TextType::class);

        $isCurrentUser = $builder->getData() instanceof User
            && $builder->getData()->getId() === $this->security->getUser()->getId();

        if ($isCurrentUser) {
            if (in_array($mode, [UserFormMode::PASSWORD_CHANGE, UserFormMode::EDIT])) {
                $this->getCurrentPasswordField($builder, $mode);
            }

            if ($options['require_password'] ?? true) {
                $this->getPasswordField($builder, $mode);
            } else {
                $pswd = $this->userFactory->generatePassword($this->minPasswordLength);
                $this->getUser($builder)
                    ->setPlainPassword($pswd)
                    ->forcePasswordChange();
            }
        } else {
            $builder->add('resetPassword', SubmitType::class, [
                'label' => '
                    <i class="fas fa-key"></i>
                    Reset password
                ',
                'label_html' => true,
            ]);
        }
    }

    private function getDisabledOptions(): array
    {
        return [
            'disabled' => true,
            'attr' => ['readonly' => true]
        ];
    }

    public function getCurrentPasswordField(FormBuilderInterface $builder, UserFormMode $mode): void
    {
        $builder->add('currentPassword', PasswordType::class, [
            'mapped' => false,
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Callback(function (mixed $value, ExecutionContextInterface $context) use ($builder) {
                    $user = $this->getUser($builder);
                    if (!$this->userFactory->checkPassword($user, $value)) {
                        $context->buildViolation('Invalid old password')
                            ->atPath('currentPassword')
                            ->addViolation();
                    }
                })
            ]
        ]);
    }

    private function getUser(FormBuilderInterface $builder): User
    {
        $user = $builder->getData();
        if (!$user instanceof User) {
            throw new LogicException('User expected');
        }

        return $user;
    }

    public function getPasswordField(FormBuilderInterface $builder, UserFormMode $mode): void
    {
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'require_password' => true,
            'mode' => UserFormMode::CREATE,
            'createdBy' => $this->security->getUser(),
        ]);
        $resolver->setAllowedTypes('mode', UserFormMode::class);
    }
}
