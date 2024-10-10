<?php

namespace App\Form;

use App\Entity\Posting;
use App\Repository\PostingRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class PostingType extends AbstractType
{
    public function __construct(
//        private readonly PostingRepository $postingRepository
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
//                'constraints' => [
//                    new Assert\Callback(function ($title, $context) {
//                        $posting = $this->postingRepository->findOneBy(['title' => $title]);
//                        if ($posting) {
//                            $context->buildViolation('A posting with that title already exists')
//                                ->atPath('title')
//                                ->addViolation();
//                        }
//                    })
//                ]
            ])
            ->add('description');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posting::class,
        ]);
    }
}
