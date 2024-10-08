<?php

namespace App\Entity\Trait;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait Disableable
{
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $disabledAt = null;

    public function getDisabledAt(): ?DateTimeImmutable
    {
        return $this->disabledAt;
    }

    public function setDisabledAt(?DateTimeImmutable $disabledAt = new DateTimeImmutable()): self
    {
        $this->disabledAt = $disabledAt;
        return $this;
    }

    public function isDisabled(): bool
    {
        return $this->disabledAt !== null;
    }

    public function disable(): self
    {
        $this->disabledAt = new DateTimeImmutable();
        return $this;
    }

    public function enable(): self
    {
        $this->disabledAt = null;
        return $this;
    }
}
