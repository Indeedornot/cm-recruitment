<?php

namespace App\Form;

use App\Entity\Posting;
use App\Security\Entity\Admin;
use App\Security\Repository\UserRepository;
use App\Security\Services\ExtendedSecurity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class PostingType extends AbstractType
{
    public function __construct(private ExtendedSecurity $security, private UserRepository $userRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'components.posting.form.title'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'components.posting.form.description'
            ])
            ->add('assignedTo', ChoiceType::class, [
                'choices' => $this->userRepository->findAdminsAll(),
                'choice_label' => function (?Admin $user): string {
                    return $user ? $user->getName() : '';
                },
                'choice_value' => 'id',
                'label' => 'components.posting.form.assigned_to',
                'constraints' => [new Assert\NotBlank()],
                'required' => true,
                'attr' => [
                    'data-hook' => 'readonlyInput',
                    'disabled' => !$this->security->isSuperAdmin()
                ]
            ])
            ->add('closingDate', DateTimeType::class)
            ->add('questionnaire', CreateQuestionnaireType::class, [
                'label' => 'components.posting.form.questionnaire',
            ]);


        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (PreSetDataEvent $event) use ($options) {
            $form = $event->getForm();
            $posting = $event->getData();
            if (empty($posting) || !empty($posting->getAssignedTo())) {
                return;
            }

            $form->get('assignedTo')->setData($options['assignedTo']);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posting::class,
            'createdBy' => $this->security->getUser(),
            'assignedTo' => $this->security->getUser(),
        ]);
    }
}
