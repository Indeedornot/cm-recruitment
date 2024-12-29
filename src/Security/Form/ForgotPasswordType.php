<?php

namespace App\Security\Form;

use App\Form\Exception;
use App\Security\Factory\UserFactory;
use App\Security\Repository\UserRepository;
use App\Security\Services\ExtendedSecurity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{ButtonType,
    EmailType,
    SubmitType,
};
use Symfony\Component\Form\Button;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

class ForgotPasswordType extends AbstractType
{
    private int $minPasswordLength = 12;

    public function __construct(
        private ExtendedSecurity $security,
        private UserFactory $userFactory,
        private UserRepository $userRepository,
        private TranslatorInterface $translator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'security.form.forgot_password.text'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'security.form.forgot_password.submit',
                'attr' => ['class' => 'btn btn-primary'],
                'label_html' => true
            ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (PostSubmitEvent $event) {
            $form = $event->getForm();
            $data = $form->getData();
            $email = $data['email'];
            $user = $this->userRepository->findBy(['email' => $email]);
            if (empty($user)) {
                return;
            }

            $user = $user[0];
            $this->userFactory->resetPassword($user);
        });
    }
}
