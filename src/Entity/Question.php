<?php

namespace App\Entity;

use App\Entity\Trait\Disableable;
use App\Entity\Trait\Identified;
use App\Entity\Trait\Timestampable;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    use Timestampable;
    use Identified;
    use Disableable;

    #[ORM\Column(type: "string", length: 255)]
    private string $questionKey;

    #[ORM\Column(type: "string", length: 255)]
    private string $expectedType;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $defaultValue;

    #[ORM\Column(type: "boolean")]
    private bool $isNullable;

    #[ORM\Column(type: "string", length: 255)]
    private string $label;

    #[ORM\Column(type: "json")]
    private array $constraints = [];

    #[ORM\ManyToMany(targetEntity: Questionnaire::class, mappedBy: 'questions')]
    private Collection $questionnaires;

    #[ORM\Column]
    private bool $forceSet;

    #[ORM\Column(type: "json")]
    private array $dependsOn = [];

    #[ORM\Column]
    private int $sortOrder = 0;

    #[ORM\Column]
    private string $formType;

    #[ORM\Column(type: 'json', options: ['default' => '{}'])]
    private array $formOptions;

    public function __construct()
    {
        $this->questionnaires = new ArrayCollection();
    }

    public function getQuestionKey(): string
    {
        return $this->questionKey;
    }

    public function setQuestionKey(string $questionKey): self
    {
        $this->questionKey = $questionKey;
        return $this;
    }

    public function getExpectedType(): string
    {
        return $this->expectedType;
    }

    public function setExpectedType(string $expectedType): self
    {
        $this->expectedType = $expectedType;
        return $this;
    }

    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(?string $defaultValue): self
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    public function getIsNullable(): bool
    {
        return $this->isNullable;
    }

    public function setIsNullable(bool $isNullable): self
    {
        $this->isNullable = $isNullable;
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

    public function getQuestionnaires(): Collection
    {
        return $this->questionnaires;
    }

    public function addQuestionnaire(Questionnaire $questionnaire): self
    {
        if (!$this->questionnaires->contains($questionnaire)) {
            $this->questionnaires[] = $questionnaire;
        }

        return $this;
    }

    public function removeQuestionnaire(Questionnaire $questionnaire): self
    {
        $this->questionnaires->removeElement($questionnaire);
        return $this;
    }

    public function getDependsOn(): array
    {
        return $this->dependsOn;
    }

    public function setDependsOn(array $dependsOn): self
    {
        $this->dependsOn = $dependsOn;
        return $this;
    }

    public function isForceSet(): bool
    {
        return $this->forceSet;
    }

    public function setForceSet(bool $forceSet): self
    {
        $this->forceSet = $forceSet;
        return $this;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    public function getFormOptions(): array
    {
        return $this->formOptions ?? [];
    }

    public function setFormOptions(array $formOptions): self
    {
        $this->formOptions = $formOptions;
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
}
