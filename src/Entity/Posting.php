<?php

namespace App\Entity;

use App\Entity\Trait\CreatedByAdmin;
use App\Entity\Trait\Disableable;
use App\Entity\Trait\Identified;
use App\Entity\Trait\Timestampable;
use App\Repository\PostingRepository;
use App\Security\Entity\Admin;
use App\Security\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_TITLE', fields: ['title'])]
#[UniqueEntity(fields: ['title'], message: 'A posting with that title already exists')]
#[ORM\Entity(repositoryClass: PostingRepository::class)]
class Posting
{
    use Timestampable;
    use Disableable;
    use Identified;
    use CreatedByAdmin;

    #[ORM\Column]
    private string $title;

    #[ORM\Column]
    private string $description;
    #[ORM\OneToMany(targetEntity: PostingQuestion::class, mappedBy: 'posting', cascade: ['persist', 'remove'])]
    private Collection $questions;
    #[ORM\OneToMany(targetEntity: ClientApplication::class, mappedBy: 'posting')]
    private Collection $clientApplications;
    #[ORM\ManyToOne(targetEntity: Admin::class)]
    private Admin $assignedTo;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->clientApplications = new ArrayCollection();
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

    public function getQuestions(): Collection
    {
        return $this->questions->filter(fn(PostingQuestion $question) => $question->getDisabledAt() === null);
    }

    public function setQuestions(Collection $questions): Posting
    {
        $this->questions = $questions;
        return $this;
    }


    public function addQuestion(PostingQuestion $question): Posting
    {
        if (!$this->questions->contains($question)) {
            $question->setPosting($this);
            $this->questions->add($question);
        }
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

    public function removeQuestion(PostingQuestion $question): Posting
    {
        $this->questions->removeElement($question);
        $question->setDisabledAt(new DateTimeImmutable());
        return $this;
    }
}
