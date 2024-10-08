<?php

namespace App\Entity;

use App\Entity\Trait\Disableable;
use App\Entity\Trait\Identified;
use App\Entity\Trait\Timestampable;
use App\Security\Entity\Client;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

class ClientApplication
{
    use Timestampable;
    use Identified;
    use Disableable;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'applications')]
    private PersistentCollection $client;

    #[ORM\OneToOne(targetEntity: Questionnaire::class, inversedBy: 'client')]
    private Questionnaire $questionnaire;

    #[ORM\ManyToOne(targetEntity: Posting::class, inversedBy: 'clientApplications')]
    private Posting $posting;

    #[ORM\OneToMany(targetEntity: PostingAnswer::class, mappedBy: 'user')]
    private PersistentCollection $answers;
}
