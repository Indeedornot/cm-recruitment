<?php

namespace App\Entity;

use App\Repository\PostingRepository;
use App\Security\Entity\Admin;
use App\Security\Entity\User;
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

    #[ORM\ManyToOne(targetEntity: Admin::class, inversedBy: 'postings')]
    private Admin $createdBy;

    #[ORM\ManyToOne(targetEntity: Admin::class, inversedBy: 'postings')]
    private Admin $assignedTo;

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

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(Admin $createdBy): Posting
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getAssignedTo(): User
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(Admin $assignedTo): Posting
    {
        $this->assignedTo = $assignedTo;
        return $this;
    }
}
