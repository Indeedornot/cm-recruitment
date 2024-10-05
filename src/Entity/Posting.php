<?php

namespace App\Entity;

use App\Repository\PostingRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_TITLE', fields: ['title'])]
#[UniqueEntity(fields: ['title'], message: 'A posting with that title already exists')]
#[ORM\Entity(repositoryClass: PostingRepository::class)]
class Posting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $title;

    #[ORM\Column]
    private string $description;

    #[ORM\OneToMany(targetEntity: PostingQuestion::class, mappedBy: 'posting')]
    private PersistentCollection $questions;

    #[ORM\OneToMany(targetEntity: PostingAnswer::class, mappedBy: 'posting')]
    private PersistentCollection $answers;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getAnswers(): PersistentCollection
    {
        return $this->answers;
    }

    public function setAnswers(array $answers): Posting
    {
        $this->answers = $answers;
        return $this;
    }

    public function getQuestions(): PersistentCollection
    {
        return $this->questions;
    }

    public function setQuestions(array $questions): Posting
    {
        $this->questions = $questions;
        return $this;
    }
}
