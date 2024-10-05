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

    #[ORM\ManyToOne(targetEntity: PostingQuestion::class)]
    private PostingQuestion $question;

    #[ORM\ManyToOne(targetEntity: Posting::class, inversedBy: 'answers')]
    private Posting $posting;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    private Client $user;

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

    public function getPosting(): Posting
    {
        return $this->posting;
    }

    public function setPosting(Posting $posting): PostingAnswer
    {
        $this->posting = $posting;
        return $this;
    }

    public function getUser(): Client
    {
        return $this->user;
    }

    public function setUser(Client $user): PostingAnswer
    {
        $this->user = $user;
        return $this;
    }
}
