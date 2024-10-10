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
#[ORM\HasLifecycleCallbacks]
class ClientApplication
{
    use Timestampable;
    use Identified;
    use Disableable;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'applications')]
    private Client $client;

    #[ORM\OneToOne(targetEntity: Questionnaire::class, inversedBy: 'application', cascade: ['persist', 'remove'])]
    private Questionnaire $questionnaire;
    #[ORM\ManyToOne(targetEntity: Posting::class, inversedBy: 'applications')]
    private Posting $posting;
    #[ORM\OneToMany(targetEntity: PostingAnswer::class, mappedBy: 'application', cascade: ['persist', 'remove'])]
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

    public function addAnswer(PostingAnswer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $answer->setApplication($this);
            $this->answers[] = $answer;
        }
        return $this;
    }

    public function removeAnswer(PostingAnswer $answer): self
    {
//        TODO: ??
        $this->answers->removeElement($answer);
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

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;
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
}
