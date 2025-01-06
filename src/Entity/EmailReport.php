<?php

namespace App\Entity;

use App\Entity\Trait\Identified;
use App\Entity\Trait\Timestampable;
use App\Security\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class EmailReport
{
    use Identified;
    use Timestampable;

    #[ORM\Column]
    private string $subject;

    #[ORM\Column(type: 'text')]
    private string $body;

    #[ORM\Column(type: 'json', options: ['default' => '[]'])]
    private array $recipients;

    #[ORM\Column(type: 'json', options: ['default' => '[]'])]
    private array $recipientIds;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $createdBy;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $sentAt;

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function setRecipients(array $recipients): self
    {
        $this->recipients = $recipients;
        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    public function getSentAt(): DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function setSentAt(DateTimeImmutable $sentAt): self
    {
        $this->sentAt = $sentAt;
        return $this;
    }

    public function getRecipientIds(): array
    {
        return $this->recipientIds;
    }

    public function setRecipientIds(array $recipientIds): self
    {
        $this->recipientIds = $recipientIds;
        return $this;
    }
}
