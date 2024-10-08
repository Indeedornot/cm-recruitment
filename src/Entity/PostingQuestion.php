<?php

namespace App\Entity;

use App\Entity\Trait\CreatedByAdmin;
use App\Entity\Trait\Disableable;
use App\Entity\Trait\Identified;
use App\Entity\Trait\Timestampable;
use App\Repository\PostingQuestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostingQuestionRepository::class)]
class PostingQuestion
{
    use CreatedByAdmin;
    use Timestampable;
    use Disableable;
    use Identified;

    #[ORM\Column]
    private string $title;

    #[ORM\Column]
    private string $description;
    #[ORM\ManyToOne(targetEntity: Posting::class, inversedBy: 'questions')]
    private Posting $posting;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPosting(): Posting
    {
        return $this->posting;
    }

    public function setPosting(Posting $posting): PostingQuestion
    {
        $this->posting = $posting;
        return $this;
    }
}
