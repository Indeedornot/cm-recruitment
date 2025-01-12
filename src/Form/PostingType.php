<?php

namespace App\Form;

use App\Entity\Posting;
use App\Entity\PostingText;
use App\Entity\Schedule;
use App\Repository\GlobalConfigRepository;
use App\Security\Entity\Admin;
use App\Security\Repository\UserRepository;
use App\Security\Services\ExtendedSecurity;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSetDataEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CopyTextRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @template-implements BaseFormTypeTrait<Posting>
 */
class PostingType extends AbstractType
{
    use BaseFormTypeTrait;

    public function __construct(
        private ExtendedSecurity $security,
        private UserRepository $userRepository,
        private CopyTextRepository $copyTextRepository,
        private GlobalConfigRepository $globalConfigRepository,
        private ValidatorInterface $validator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $this->getData($builder);
        if (empty($data)) {
            $data = new Posting();
        }

        if (empty($data->getAssignedTo())) {
            $data->setAssignedTo($this->security->getUser());
        }

        // Ensure at least one Schedule is present
        if ($data->getSchedules()->isEmpty()) {
            $data->addSchedule(new Schedule());
        }

        $builder
            ->add('title', TextType::class, [
                'label' => 'components.posting.form.title'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'components.posting.form.description',
                'required' => false,
                'empty_data' => ''
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
            ->add('closingDate', DateTimeType::class, [
                'label' => 'components.posting.form.closing_date',
            ])
            ->add('schedules', CollectionType::class, [
                'entry_type' => ScheduleType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'label' => 'components.schedule.form.label',
            ]);

        /** @var Posting|null $posting */
        $posting = $builder->getData();
        if (empty($posting) || $posting->getApplications()->isEmpty()) {
            $builder
                ->add('questionnaire', CreateQuestionnaireType::class, [
                    'label' => 'components.posting.form.questionnaire',
                ]);
        }

        $this->addCopyTextFields($builder);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (PostSetDataEvent $event) {
            $closingDate = $event->getForm()->get('closingDate');
            if ($closingDate->getData() === null) {
                $date = $this->globalConfigRepository->getValue('closing_date');
                if (!empty($date)) {
                    $closingDate->setData(new DateTimeImmutable($date));
                }
            }
        });
    }

    private function addCopyTextFields(FormBuilderInterface $builder): void
    {
        /** @var Posting $data */
        $data = $this->getData($builder);
        foreach ($this->copyTextRepository->findAll() as $copyText) {
            $text = $data?->getCopyText($copyText->getKey());
            $builder->add('copy_' . $copyText->getKey(), $copyText->getFormType(), array_merge([
                'label' => $copyText->getLabel(),
                'required' => $copyText->isRequired(),
                'constraints' => $copyText->getConstraints(),
                'data' => $text ? $text->getValue() : $copyText->getDefaultValue(),
                'mapped' => false,
            ], $copyText->getFormOptions()));
        }

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $posting = $this->getData($event);

            foreach ($this->copyTextRepository->findAll() as $copyText) {
                $formField = $form->get('copy_' . $copyText->getKey());
                $value = $this->getIData($formField);

                $previous = $posting->getCopyText($copyText->getKey());
                $postingText = $previous ?? new PostingText();
                $postingText->setCopyText($copyText);
                $postingText->setValue($value);
                if ($previous === null) {
                    $posting->addCopyText($postingText);
                }
            }
        }, 1);
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
