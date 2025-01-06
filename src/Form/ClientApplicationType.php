<?php

namespace App\Form;

use App\Entity\ClientApplication;
use App\Entity\QuestionnaireAnswer;
use App\Repository\QuestionnaireAnswerRepository;
use App\Security\Services\ExtendedSecurity;
use App\Services\Posting\QuestionService;
use LogicException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\ApplyQuestionnaireType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
