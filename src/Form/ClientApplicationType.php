<?php

namespace App\Form;

use App\Entity\ClientApplication;
use App\Entity\Schedule;
use App\Repository\QuestionnaireAnswerRepository;
use App\Security\Services\ExtendedSecurity;
use App\Services\Posting\QuestionService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Webmozart\Assert\Assert;

class ClientApplicationType extends AbstractType
{
    public function __construct(
        private ExtendedSecurity $security,
        private QuestionnaireAnswerRepository $answerRepository,
        private readonly ValidatorInterface $validator,
        private QuestionService $questionService,
        private TranslatorInterface $translator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ClientApplication $application */
        $application = $builder->getData();
        $posting = $application->getPosting();
        $questions = $posting->getQuestionnaire()->getQuestions();

        Assert::isInstanceOf($application, ClientApplication::class);

        $scheduleCount = $posting->getSchedules()->count();
        $builder->add('schedule', ChoiceType::class, [
            'choices' => $posting->getSchedules()->toArray(),
            'choice_label' => function (Schedule $schedule) {
                return $schedule->getTime() . ' (' . $this->translator->trans('components.posting.form.limit') . ' ' . $schedule->getPersonLimit() . ')';
            },
            'label_html' => true,
            'label' => 'Wybierz termin',
            'required' => true,
            'disabled' => $scheduleCount === 1,
        ]);
        if ($scheduleCount === 1) {
            $application->setSchedule($posting->getSchedules()->first());
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
