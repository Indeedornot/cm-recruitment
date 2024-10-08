<?php

namespace App\Entity;

use App\Repository\PostingAnswerRepository;
use App\Security\Entity\Client;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostingAnswerRepository::class)]
class PostingAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: PostingQuestion::class, inversedBy: 'id')]
    private PostingQuestion $question;

    #[ORM\ManyToOne(targetEntity: ClientApplication::class, inversedBy: 'answers')]
    private ClientApplication $user;

    #[ORM\Column]
    private string $answer;

    public function getId(): ?int
    {
        return $this->id;
    }

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

    public function getUser(): ClientApplication
    {
        return $this->user;
    }

    public function setUser(ClientApplication $user): static
    {
        $this->user = $user;
        return $this;
    }
}
