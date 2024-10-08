<?php

namespace App\Entity;

use App\Entity\Trait\Identified;
use App\Repository\PostingAnswerRepository;
use App\Security\Entity\Client;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostingAnswerRepository::class)]
class PostingAnswer
{
    use Identified;

    #[ORM\ManyToOne(targetEntity: PostingQuestion::class, inversedBy: 'id')]
    private PostingQuestion $question;
    #[ORM\ManyToOne(targetEntity: Questionnaire::class, inversedBy: 'answers')]
    private Questionnaire $questionnaire;

    #[ORM\Column]
    private string $answer;

    public function getQuestion(): PostingQuestion
    {
        return $this->question;
    }

    public function setQuestion(PostingQuestion $question): PostingAnswer
    {
        $this->question = $question;
        return $this;
    }

    public function getAnswer(): string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): PostingAnswer
    {
        $this->answer = $answer;
        return $this;
    }

    public function getQuestionnaire(): Questionnaire
    {
        return $this->questionnaire;
    }

    public function setQuestionnaire(Questionnaire $questionnaire): self
    {
        $this->questionnaire = $questionnaire;
        return $this;
    }
}
