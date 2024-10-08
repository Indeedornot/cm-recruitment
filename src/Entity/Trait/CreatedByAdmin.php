<?php

namespace App\Entity\Trait;

use App\Security\Entity\Admin;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait CreatedByAdmin
{
    #[ORM\Column]
    #[ORM\ManyToOne(targetEntity: Admin::class)]
    public Admin $createdBy;

    public function getCreatedBy(): Admin
    {
        return $this->createdBy;
    }

    public function setCreatedBy(Admin $createdBy): self
    {
        $this->createdBy = $createdBy;
        return $this;
    }
}
