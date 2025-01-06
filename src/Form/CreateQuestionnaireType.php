<?php

namespace App\Form;

use App\Contract\PhoneNumber\Form\Type\PhoneNumberType;
use App\Entity\Question;
use App\Entity\Questionnaire;
use App\Repository\QuestionnaireRepository;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateQuestionnaireType extends AbstractType
{

    public function __construct(
        private QuestionRepository $questionRepository,
        private ValidatorInterface $validator,
        private QuestionnaireRepository $questionnaireRepository
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $questions = $this->questionRepository->findAll();
        $forceSet = array_filter($questions, fn(Question $question) => $question->isForceSet());

        $disabled = [];
        $toCheck = $forceSet;
        while (!empty($toCheck)) {
            $question = array_pop($toCheck);
            if (in_array($question->getQuestionKey(), $disabled)) {
                continue;
            }
            $disabled[] = $question->getQuestionKey();
            $toCheck = array_merge($toCheck,
                array_filter($questions, fn(Question $q) => in_array($q->getQuestionKey(), $question->getDependsOn()))
            );
        }

        $data = $builder->getData();
        if (!empty($data)) {
            $data = $data['questions'];
            if ($data instanceof PersistentCollection) {
                $data = $data->toArray();
            }

            $prevKeys = array_map(fn(Question $q) => $q->getQuestionKey(), $data);
            $disabled = array_merge($disabled,
                array_diff($prevKeys, array_map(fn(Question $q) => $q->getQuestionKey(), $forceSet)));
        }

        $builder
            ->add('questions', ChoiceType::class, [
                'choices' => $questions,
                'choice_label' => function (?Question $question): string {
                    return $question ? $question->getLabel() : '';
                },
                'label_html' => true,
                'by_reference' => true,
                'choice_value' => 'id',
                'multiple' => true,
                'expanded' => true,
                'label' => false,
                'constraints' => [new Assert\Count(['min' => 1])],
                'choice_attr' => function (?Question $question) use ($disabled) {
                    if (!$question) {
                        return [];
                    }

                    $attrs = [];

                    $attrs['data-question-key'] = $question->getQuestionKey();
                    if (!empty($question->getDependsOn())) {
                        $attrs['data-depends-on'] = json_encode($question->getDependsOn());
                    }

                    if (in_array($question->getQuestionKey(), $disabled)) {
                        $attrs['checked'] = 'checked';
                        $attrs['disabled'] = 'disabled';
                        $attrs['class'] = 'required';
                        $attrs['data-hook'] = 'readonlyInput';
                    }

                    return $attrs;
                },
            ]);

        $builder->get('questions')->addModelTransformer(
            new CallbackTransformer(
                static fn($q) => $q instanceof Collection ? $q->toArray() : $q,
                static fn($q) => $q
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Questionnaire::class,
        ]);
    }
}
