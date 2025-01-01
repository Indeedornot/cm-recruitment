<?php

namespace App\Entity;

use App\Entity\Trait\Disableable;
use App\Entity\Trait\Identified;
use App\Entity\Trait\Timestampable;
use App\Repository\CopyTextRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint;

#[ORM\Entity(repositoryClass: CopyTextRepository::class)]
#[ORM\HasLifecycleCallbacks]
class CopyText
{
    use Identified;
    use Disableable;
    use Timestampable;

    #[ORM\Column(type: 'text', unique: true)]
    private string $key;

    #[ORM\Column]
    private string $label;

    #[ORM\Column(type: 'json')]
    private ?string $defaultValue;

    #[ORM\Column]
    private bool $required;

    #[ORM\Column(type: 'json')]
    private array $constraints;

    #[ORM\Column(type: 'text')]
    private string $formType;

    #[ORM\Column(type: 'json')]
    private array $formOptions;

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return Constraint[]
     */
    public function getConstraints(): array
    {
        return Dto\Constraint::unserializeArray($this->constraints);
    }

    public function setConstraints(array $constraints): self
    {
        $this->constraints = $constraints;
        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(string $defaultValue): self
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): self
    {
        $this->required = $required;
        return $this;
    }

    public function getFormType(): string
    {
        return $this->formType;
    }

    public function setFormType(string $formType): self
    {
        $this->formType = $formType;
        return $this;
    }

    public function getFormOptions(): array
    {
        return $this->formOptions;
    }

    public function setFormOptions(array $formOptions): self
    {
        $this->formOptions = $formOptions;
        return $this;
    }
}
