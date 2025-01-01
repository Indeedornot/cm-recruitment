<?php

namespace App\Entity;

use App\Entity\Trait\Identified;
use App\Entity\Trait\Timestampable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class PostingText
{
    use Identified;
    use Timestampable;

    #[ORM\ManyToOne(targetEntity: Posting::class, inversedBy: 'copyTexts')]
    private Posting $posting;

    #[ORM\ManyToOne(targetEntity: CopyText::class)]
    private CopyText $copyText;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $value = null;

    public function getPosting(): Posting
    {
        return $this->posting;
    }

    public function setPosting(Posting $posting): self
    {
        $this->posting = $posting;
        return $this;
    }

    public function getCopyText(): CopyText
    {
        return $this->copyText;
    }

    public function setCopyText(CopyText $copyText): self
    {
        $this->copyText = $copyText;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;
        return $this;
    }
}
