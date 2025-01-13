<?php

namespace App\Entity;

use App\Contract\Validator\ContainerAwareConstraint;
use App\Entity\Trait\Identified;
use App\Entity\Trait\Timestampable;
use App\Repository\GlobalConfigRepository;
use App\Repository\QuestionnaireAnswerRepository;
use Doctrine\ORM\Mapping as ORM;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: QuestionnaireAnswerRepository::class)]
#[ORM\HasLifecycleCallbacks]
class QuestionnaireAnswer
{
    use Timestampable;
    use Identified;

    #[ORM\ManyToOne(targetEntity: ClientApplication::class, inversedBy: 'answers')]
    private ClientApplication $application;

    #[ORM\ManyToOne(targetEntity: Question::class)]
    private Question $question;

    #[ORM\Column(type: 'json')]
    private mixed $answer;

    #[ContainerAwareConstraint]
    public function validate(
        ExecutionContextInterface $context,
        mixed $group,
        ContainerInterface $container
    ): void {
        $this->validateAge($context, $container);
        $this->validateBonusCriteria($context, $container);
    }

    private function validateBonusCriteria(ExecutionContextInterface $context, ContainerInterface $container): void
    {
        if ($this->question->getQuestionKey() !== 'bonus_criteria') {
            return;
        }

        if (empty($this->answer)) {
            return;
        }

        if (!in_array('additional_docs', $this->answer)) {
            $context->buildViolation('components.posting.question.bonus_criteria_consent')
                ->addViolation();
        }
    }

    private function validateAge(ExecutionContextInterface $context, ContainerInterface $container): void
    {
        if ($this->question->getQuestionKey() !== 'age') {
            return;
        }

//        $applicationPhase = $this->getApplication()->getDataByKey('application_phase');
        $applicationPhase = $container->get(GlobalConfigRepository::class)->getValue('application_phase');
        if ($applicationPhase === 'continuation') {
            return;
        }

        $posting = $this->application->getPosting();
        $minAge = $posting->getCopyText('age_min')?->getValue();
        $maxAge = $posting->getCopyText('age_max')?->getValue();
        $errorMessage = match ([$minAge !== null, $maxAge !== null]) {
            [true, true] => ['notInRangeMessage' => 'components.posting.question.age.notInRangeMessage'],
            [true, false] => ['minMessage' => 'components.posting.question.age.minMessage'],
            [false, true] => ['maxMessage' => 'components.posting.question.age.maxMessage'],
            default => null
        };
        if ($errorMessage === null) {
            return;
        }

        $context->getValidator()->inContext($context)->validate($this->answer, [
            new Assert\Range([
                'min' => $minAge,
                'max' => $maxAge,
                ...$errorMessage
            ])
        ]);
    }

    public function getAnswer(): mixed
    {
        return $this->answer;
    }

    public function setAnswer(mixed $answer): self
    {
        $this->answer = $answer;
        return $this;
    }

    public function getApplication(): ClientApplication
    {
        return $this->application;
    }

    public function setApplication(ClientApplication $application): self
    {
        $this->application = $application;
        return $this;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): self
    {
        $this->question = $question;
        return $this;
    }
}
