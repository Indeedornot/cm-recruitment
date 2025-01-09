<?php

namespace App\Form;

use App\Entity\ClientApplication;
use App\Entity\SubPosting;
use App\Repository\QuestionnaireAnswerRepository;
use App\Security\Services\ExtendedSecurity;
use App\Services\Posting\QuestionService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;

class ClientApplicationType extends AbstractType
{
    public function __construct(
        private ExtendedSecurity $security,
        private QuestionnaireAnswerRepository $answerRepository,
        private readonly ValidatorInterface $validator,
        private QuestionService $questionService
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ClientApplication $application */
        $application = $builder->getData();
        $posting = $application->getPosting();
        $questions = $posting->getQuestionnaire()->getQuestions();

        Assert::isInstanceOf($application, ClientApplication::class);

        if (!$posting->getSubPostings()->isEmpty()) {
            $builder->add('subPosting', ChoiceType::class, [
                'choices' => $posting->getSubPostings()->toArray(),
                'choice_label' => function (SubPosting $subPosting) {
                    return $subPosting->getTitle() . ' ' . $subPosting->getTime();
                },
                'choice_name' => 'title',
                'label' => 'Wybierz podzajęcia',
                'required' => true,
            ]);
        }
        $this->questionService->addQuestions($builder, $questions);
        $this->questionService->fillPreviousAnswers($builder, $questions, $application->getAnswers());
        $this->questionService->addAnswerSubmitHandler($builder);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientApplication::class,
            'client' => $this->security->getUser(),
        ]);
    }
}
