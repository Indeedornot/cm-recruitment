<?php

namespace App\Entity;

use App\Entity\Trait\Identified;
use App\Entity\Trait\Timestampable;
use App\Repository\SubPostingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: SubPostingRepository::class)]
#[ORM\HasLifecycleCallbacks]
class SubPosting
{
    use Identified;
    use Timestampable;

    #[ORM\ManyToOne(targetEntity: Posting::class, inversedBy: 'subPostings')]
    private Posting $posting;

    #[ORM\OneToMany(targetEntity: ClientApplication::class, mappedBy: 'subPosting')]
    private Collection $applications;

    #[ORM\Column(type: 'string')]
    private string $title;

    #[ORM\Column(type: 'string')]
    private string $time;

    #[ORM\Column(type: 'integer')]
    private int $personLimit;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
    }

    public function getPersonLimit(): int
    {
        return $this->personLimit;
    }

    public function setPersonLimit(int $personLimit): self
    {
        $this->personLimit = $personLimit;
        return $this;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public function setTime(string $time): self
    {
        $this->time = $time;
        return $this;
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

    public function getPosting(): Posting
    {
        return $this->posting;
    }

    public function setPosting(Posting $posting): self
    {
        $this->posting = $posting;
        return $this;
    }
}
