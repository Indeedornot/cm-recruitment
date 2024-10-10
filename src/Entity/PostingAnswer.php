<?php

namespace App\Entity;

use App\Entity\Trait\Identified;
use App\Repository\PostingAnswerRepository;
use App\Security\Entity\Client;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostingAnswerRepository::class)]
class PostingAnswer
{
    use Identified;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: PostingQuestion::class, inversedBy: 'id')]
    private PostingQuestion $question;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: ClientApplication::class, inversedBy: 'answers')]
    private ClientApplication $application;

    #[ORM\Column]
    private string $answer;

    public function getAnswer(): string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): PostingAnswer
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

    public function getQuestion(): PostingQuestion
    {
        return $this->question;
    }

    public function setQuestion(PostingQuestion $question): self
    {
        $this->question = $question;
        return $this;
    }
}
