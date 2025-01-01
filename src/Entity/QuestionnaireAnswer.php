<?php

namespace App\Entity;

use App\Entity\Trait\Identified;
use App\Entity\Trait\Timestampable;
use App\Repository\QuestionnaireAnswerRepository;
use Doctrine\ORM\Mapping as ORM;
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

    #[ORM\Column]
    private string $answer;

    #[Assert\Callback]
    public function validateAge(ExecutionContextInterface $context): void
    {
        if ($this->question->getQuestionKey() !== 'age') {
            return;
        }

        $posting = $this->application->getPosting();
        $minAge = $posting->getCopyText('age_min')?->getValue();
        $maxAge = $posting->getCopyText('age_max')?->getValue();

        $context->getValidator()->inContext($context)->validate($this->answer, [
            new Assert\Range([
                'min' => $minAge,
                'max' => $maxAge,
            ])
        ]);
    }

    public function getAnswer(): string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
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
