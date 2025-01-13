<?php

namespace App\Form;

use App\Contract\PhoneNumber\Form\Type\PhoneNumberType;
use App\Entity\Question;
use App\Entity\Questionnaire;
use App\Repository\CopyTextRepository;
use App\Repository\QuestionnaireRepository;
use App\Repository\QuestionRepository;
use App\Security\Entity\Admin;
use App\Security\Entity\User;
use App\Security\Repository\UserRepository;
use App\Services\Posting\CopyTextService;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PostingDisplayType extends AbstractType
{
    public function __construct(
        private UserRepository $userRepository,
        private CopyTextRepository $copyTextRepository,
        private CopyTextService $copyTextService
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, [
                'label' => 'user.posting_list.filter.text',
                'required' => false,
            ])
            ->add('age', NumberType::class, [
                'label' => 'user.posting_list.filter.age',
                'required' => false,
                'attr' => [
                    'min' => 0,
                ],
            ])
            ->add('schedule', ChoiceType::class, [
                'choices' => [
                    '' => null,
                    'common.weekday.monday' => 'poniedziałek',
                    'common.weekday.tuesday' => 'wtorek',
                    'common.weekday.wednesday' => 'środa',
                    'common.weekday.thursday' => 'czwartek',
                    'common.weekday.friday' => 'piątek',
                    'common.weekday.saturday' => 'sobota',
                    'common.weekday.sunday' => 'niedziela',
                ],
                'label' => 'user.posting_list.filter.schedule',
                'required' => false
            ]);

        $categoryCp = $this->copyTextRepository->findOneBy(['key' => 'category', 'disabledAt' => null]);
        if (!empty($categoryCp)) {
            $choices = $this->copyTextService->getFormOptions($categoryCp);

            $builder->add('category', ChoiceType::class, array_merge($choices, [
                'label' => 'user.posting_list.filter.category',
                'required' => false,
            ]));
        }

        $builder->add('assignedTo', ChoiceType::class, [
            'choices' => $this->userRepository->findAdminsAll(),
            'choice_label' => function (?Admin $user): string {
                return $user->getName();
            },
            'label' => 'user.posting_list.filter.assigned_to',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'itemsPerPage' => 10,
        ]);
    }
}
