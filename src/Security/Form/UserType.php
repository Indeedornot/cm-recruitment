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
    HiddenType,
    PasswordType,
    RepeatedType,
    SubmitType,
    TextType
};
use Symfony\Component\Form\Button;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\Form\SubmitButtonTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserType extends AbstractType
{
    private int $minPasswordLength = 12;

    public function __construct(
        private ExtendedSecurity $security,
        private UserFactory $userFactory,
        private TranslatorInterface $translator
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
            ->add('email', EmailType::class, [
                'label' => 'security.form.email'
            ])
            ->add('name', TextType::class, [
                'label' => 'security.form.name',
            ]);

        $isCurrentUser = $builder->getData() instanceof User && $this->security->isLoggedIn()
            && $builder->getData()->getId() === $this->security->getUser()->getId();

        if ($isCurrentUser || $mode === UserFormMode::CREATE) {
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
                'label' => 'security.form.reset_password.submit',
                'label_html' => true,
            ]);
        }

        $builder->add('mode', HiddenType::class, [
            'data' => $mode->name,
            'mapped' => false
        ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (PostSubmitEvent $e) use ($builder, $mode) {
            if ($e->getForm()->has('resetPassword') && $e->getForm()->get('resetPassword')->isClicked()) {
                $this->userFactory->resetPassword($this->getUser($builder));
            }
        });
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
            'label' => 'security.form.current_password',
            'mapped' => false,
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Callback(function (mixed $value, ExecutionContextInterface $context) use ($builder) {
                    $user = $this->getUser($builder);
                    if (!$this->userFactory->checkPassword($user, $value)) {
                        $context->buildViolation($this->translator->trans('security.form.error.invalid_old_password'))
                            ->atPath('currentPassword')
                            ->addViolation();
                    }

                    $newPassword = $context->getRoot()->get('plainPassword')->getData();
                    if ($newPassword && $this->userFactory->checkPassword($user, $newPassword)) {
                        $context->buildViolation($this->translator->trans('security.form.error.same_password'))
                            ->atPath('plainPassword')
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
                'label' => 'security.form.password',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => $this->minPasswordLength])
                ],
            ],
            'second_options' => ['label' => 'security.form.password_confirmation'],
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
