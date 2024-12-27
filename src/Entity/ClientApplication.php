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

    #[ORM\ManyToOne(targetEntity: Posting::class, inversedBy: 'applications')]
    private Posting $posting;

    #[ORM\OneToMany(targetEntity: QuestionnaireAnswer::class, mappedBy: 'application', cascade: ['persist', 'remove'])]
    private Collection $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(QuestionnaireAnswer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setApplication($this);
        }
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

    public function getValueByKey(string $key): ?string
    {
        foreach ($this->answers as $answer) {
            if ($answer->getQuestion()->getKey() === $key) {
                return $answer->getValue();
            }
        }
        return null;
    }
}
