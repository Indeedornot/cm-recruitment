<?php

namespace App\Entity;

use App\Repository\PostingQuestionRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostingQuestionRepository::class)]
class PostingQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $title;

    #[ORM\Column]
    private string $description;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $disabledAt;

    #[ORM\ManyToOne(targetEntity: Posting::class, inversedBy: 'questions')]
    private Posting $posting;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPosting(): Posting
    {
        return $this->posting;
    }

    public function setPosting(Posting $posting): PostingQuestion
    {
        $this->posting = $posting;
        return $this;
    }

    public function getDisabledAt(): ?DateTimeImmutable
    {
        return $this->disabledAt;
    }

    public function setDisabledAt(?DateTimeImmutable $disabledAt): static
    {
        $this->disabledAt = $disabledAt;
        return $this;
    }
}
