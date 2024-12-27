<?php

namespace App\Entity;

use App\Contract\PhoneNumber\Doctrine\PhoneNumberType;
use App\Entity\Trait\Identified;
use App\Entity\Trait\Timestampable;
use App\Repository\PostingRepository;
use App\Repository\QuestionnaireRepository;
use libphonenumber\PhoneNumber;
use Doctrine\ORM\Mapping as ORM;
use libphonenumber\RegionCode;
use Symfony\Component\Validator\Constraints as Assert;
use App\Contract\PhoneNumber\Validator\PhoneNumber as PhoneNumberConstraint;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: QuestionnaireRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Questionnaire
{
    use Timestampable;
    use Identified;

    #[ORM\OneToMany(targetEntity: ClientApplication::class, mappedBy: 'questionnaire')]
    private Collection $applications;

    #[ORM\OneToMany(targetEntity: QuestionnaireAnswer::class, mappedBy: 'questionnaire', cascade: [
        'persist',
        'remove'
    ])]
    private Collection $answers;

    #[ORM\ManyToMany(targetEntity: Question::class, inversedBy: 'questionnaires', cascade: [
        'persist',
        'remove'
    ])]
    private Collection $questions;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->applications = new ArrayCollection();
        $this->questions = new ArrayCollection();
    }

    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(ClientApplication $application): self
    {
        if (!$this->applications->contains($application)) {
            $this->applications[] = $application;
            $application->setQuestionnaire($this);
        }

        return $this;
    }

    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(QuestionnaireAnswer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
        }

        return $this;
    }

    public function removeAnswer(QuestionnaireAnswer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
//            TODO: ??
            // set the owning side to null (unless already changed)
//            if ($answer->getQuestionnaire() === $this) {
//                $answer->setQuestionnaire(null);
//            }
        }

        return $this;
    }

    /**
     * @return Collection<Question>
     */
    public function getQuestions(): Collection
    {
        $questions = $this->questions->toArray();
        usort($questions, function (Question $a, Question $b) {
            return $a->getSortOrder() <=> $b->getSortOrder();
        });
        return new ArrayCollection($questions);
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->addQuestionnaire($this);

            $questions = $this->questions->toArray();
            usort($questions, function (Question $a, Question $b) {
                return $a->getSortOrder() <=> $b->getSortOrder();
            });

            $this->questions = new ArrayCollection($questions);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            $question->removeQuestionnaire($this);
        }

        return $this;
    }
}
