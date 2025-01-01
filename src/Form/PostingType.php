<?php

namespace App\Form;

use App\Entity\Posting;
use App\Entity\PostingText;
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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CopyTextRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\FormError;

class PostingType extends AbstractType
{
    public function __construct(
        private ExtendedSecurity $security,
        private UserRepository $userRepository,
        private CopyTextRepository $copyTextRepository
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
                'label' => 'components.posting.form.closing_date'
            ])
            ->add('questionnaire', CreateQuestionnaireType::class, [
                'label' => 'components.posting.form.questionnaire',
            ]);

        $this->addCopyTextFields($builder);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            /** @var Posting $posting */
            $posting = $event->getData();

            foreach ($this->copyTextRepository->findAll() as $copyText) {
                $value = $form->get('copy_' . $copyText->getKey())->getData();

                $previous = $posting->getCopyText($copyText->getKey());
                $postingText = $previous ?? new PostingText();
                $postingText->setCopyText($copyText);
                $postingText->setValue($value);
                if ($previous === null) {
                    $posting->addCopyText($postingText);
                }
            }
        });
    }

    private function addCopyTextFields(FormBuilderInterface $builder): void
    {
        /** @var ?Posting $data */
        $data = $builder->getData();
        foreach ($this->copyTextRepository->findAll() as $copyText) {
            $text = $data->getCopyText($copyText->getKey());
            $builder->add('copy_' . $copyText->getKey(), $copyText->getFormType(), array_merge([
                'label' => $copyText->getLabel(),
                'required' => $copyText->isRequired(),
                'constraints' => $copyText->getConstraints(),
                'data' => $text ? $text->getValue() : $copyText->getDefaultValue(),
                'mapped' => false,
            ], $copyText->getFormOptions()));
        }
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
