<?php

namespace App\Entity;

use App\Entity\Trait\CreatedByAdmin;
use App\Entity\Trait\Disableable;
use App\Entity\Trait\Identified;
use App\Entity\Trait\Timestampable;
use App\Repository\PostingRepository;
use App\Security\Entity\Admin;
use App\Security\Entity\User;
use App\Security\Entity\UserRoles;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_TITLE', fields: ['title'])]
#[UniqueEntity(fields: ['title'], message: 'A posting with that title already exists')]
#[ORM\Entity(repositoryClass: PostingRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Posting
{
    use Timestampable;
    use Disableable;
    use Identified;
    use CreatedByAdmin;

    #[ORM\Column]
    private string $title;

    #[ORM\Column(type: Types::STRING, nullable: false, options: ['default' => ''])]
    #[Assert\NotNull]
    private string $description = '';
    #[ORM\OneToMany(targetEntity: ClientApplication::class, mappedBy: 'posting')]
    private Collection $applications;
    #[ORM\ManyToOne(targetEntity: Admin::class)]
    private Admin $assignedTo;

    #[ORM\ManyToOne(targetEntity: Questionnaire::class, cascade: ['persist', 'remove'], inversedBy: 'posting')]
    private Questionnaire $questionnaire;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $closingDate;

    #[ORM\OneToMany(targetEntity: PostingText::class, mappedBy: 'posting', cascade: [
        'persist',
        'remove'
    ], orphanRemoval: true)]
    private Collection $copyTexts;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
        $this->copyTexts = new ArrayCollection();
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

    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function setApplications(Collection $applications): self
    {
        $this->applications = $applications;
        return $this;
    }

    public function canEdit(User $user): bool
    {
        return $user->getId() === $this->getCreatedBy()->getId() || $user->getId() === $this->getAssignedTo()->getId()
            || $user->hasRole(UserRoles::SUPER_ADMIN);
    }

    public function getAssignedTo(): ?Admin
    {
        return $this->assignedTo ?? null;
    }

    public function setAssignedTo(Admin $assignedTo): Posting
    {
        $this->assignedTo = $assignedTo;
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

    public function getClosingDate(): DateTimeImmutable
    {
        return $this->closingDate;
    }

    public function setClosingDate(DateTimeImmutable $closingDate): self
    {
        $this->closingDate = $closingDate;
        return $this;
    }

    public function isClosed(): bool
    {
        return $this->closingDate < new DateTimeImmutable();
    }

    /**
     * @return Collection<int, PostingText>
     */
    public function getCopyTexts(): Collection
    {
        return $this->copyTexts;
    }

    public function getCopyText(string $key): ?PostingText
    {
        return $this->copyTexts->findFirst(static fn(int $k, PostingText $pt) => $pt->getCopyText()->getKey() === $key);
    }

    public function addCopyText(PostingText $copyText): self
    {
        if (!$this->copyTexts->contains($copyText)) {
            $this->copyTexts->add($copyText);
            $copyText->setPosting($this);
        }
        return $this;
    }

    public function removeCopyText(PostingText $copyText): self
    {
        if ($this->copyTexts->removeElement($copyText)) {
            if ($copyText->getPosting() === $this) {
                $copyText->setPosting(null);
            }
        }
        return $this;
    }
}
