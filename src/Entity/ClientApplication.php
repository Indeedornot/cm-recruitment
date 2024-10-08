<?php

namespace App\Entity;

use App\Entity\Trait\Disableable;
use App\Entity\Trait\Identified;
use App\Entity\Trait\Timestampable;
use App\Repository\ClientApplicationRepository;
use App\Security\Entity\Client;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientApplicationRepository::class)]
class ClientApplication
{
    use Timestampable;
    use Identified;
    use Disableable;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'applications')]
    private Client $client;

    #[ORM\OneToOne(targetEntity: Questionnaire::class, inversedBy: 'client')]
    private Questionnaire $questionnaire;
    #[ORM\ManyToOne(targetEntity: Posting::class, inversedBy: 'clientApplications')]
    private Posting $posting;
    #[ORM\OneToMany(targetEntity: PostingAnswer::class, mappedBy: 'user')]
    private Collection $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function setAnswers(Collection $answers): self
    {
        $this->answers = $answers;
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

    public function getQuestionnaire(): Questionnaire
    {
        return $this->questionnaire;
    }

    public function setQuestionnaire(Questionnaire $questionnaire): self
    {
        $this->questionnaire = $questionnaire;
        return $this;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;
        return $this;
    }
}
