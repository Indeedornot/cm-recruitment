<?php

namespace App\Entity;


use App\Entity\Trait\Disableable;
use App\Entity\Trait\Identified;
use App\Entity\Trait\Timestampable;
use App\Repository\BonusCriteriaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BonusCriteriaRepository::class)]
#[ORM\HasLifecycleCallbacks]
class BonusCriteria
{
    use Identified;
    use Timestampable;
    use Disableable;

    #[ORM\Column(type: 'string', length: 255)]
    private string $label;

    #[ORM\Column(type: 'string', length: 255)]
    private string $key;

    #[ORM\Column(type: 'json', options: ['default' => '{}'])]
    private array $value;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $sortOrder = 0;

    public function getValue(string $key): mixed
    {
        return $this->value[$key] ?? null;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    public function setValue(array $value): self
    {
        $this->value = $value;
        return $this;
    }
}
