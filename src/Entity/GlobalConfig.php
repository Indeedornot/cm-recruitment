<?php

namespace App\Entity;

use App\Entity\Dto\Constraint;
use App\Entity\Trait\Disableable;
use App\Entity\Trait\Identified;
use App\Entity\Trait\Timestampable;
use App\Repository\GlobalConfigRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\TextType;

#[ORM\Entity(repositoryClass: GlobalConfigRepository::class)]
#[ORM\HasLifecycleCallbacks]
class GlobalConfig
{
    use Identified;
    use Timestampable;
    use Disableable;

    #[ORM\Column(length: 255)]
    private string $label;

    #[ORM\Column(length: 255)]
    private ?string $key = null;

    #[ORM\Column(type: 'json')]
    private mixed $value = null;

    #[ORM\Column]
    private string $formType = TextType::class;

    #[ORM\Column(type: 'json', options: ['default' => '{}'])]
    private array $formOptions = [];

    #[ORM\Column(type: 'json', options: ['default' => '[]'])]
    private array $constraints = [];

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): self
    {
        $this->value = $value;
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

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function getConstraints(): array
    {
        return Constraint::unserializeArray($this->constraints);
    }

    public function setConstraints(array $constraints): self
    {
        $this->constraints = $constraints;
        return $this;
    }
}
