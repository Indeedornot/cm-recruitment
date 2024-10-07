<?php

namespace App\Entity;

use App\Repository\PostingRepository;
use App\Security\Entity\Admin;
use App\Security\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(targetEntity: PostingQuestion::class, mappedBy: 'posting', cascade: ['persist', 'remove'])]
    private PersistentCollection $questions;

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

    public function getQuestions(): Collection
    {
        return $this->questions->filter(fn(PostingQuestion $question) => $question->getDisabledAt() === null);
    }

    public function setQuestions(array|PersistentCollection $questions): Posting
    {
        $this->setPersistentCollection($this->questions, $questions);
        return $this;
    }

    private function setPersistentCollection(PersistentCollection $collection, array|PersistentCollection $items): void
    {
        if ($items instanceof PersistentCollection) {
            $items = $items->toArray();
        }

        $collection->clear();
        foreach ($items as $item) {
            $collection->add($item);
        }
    }

    public function addQuestion(PostingQuestion $question): Posting
    {
        if (!$this->questions->contains($question)) {
            $question->setPosting($this);
            $this->questions->add($question);
        }
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

    public function removeQuestion(PostingQuestion $question): Posting
    {
        $this->questions->removeElement($question);
        $question->setDisabledAt(new DateTimeImmutable());
        return $this;
    }
}
