<?php

namespace App\Migration\Dto;


use App\Entity\Dto;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Webmozart\Assert\Assert;

class QuestionDto
{
    public function __construct(
        private string $questionKey,
        private string $expectedType,
        private array $constraints,
        private bool $forceSet = false,
        private bool $isNullable = false,
        private array $dependsOn = [],
        private int $sortOrder = 0,
        private ?string $label = null,
        private mixed $defaultValue = null,
        private string $formType = TextType::class,
        private array $formOptions = [],
        private ?array $additionalData = null
    ) {
        if ($this->label === null) {
            $this->label = 'components.question.' . $this->questionKey;
        }

        Assert::classExists($this->formType);
        Assert::isArray($this->formOptions);
        if (array_key_exists('choice_factory', $this->formOptions)) {
            Assert::isArray($this->formOptions['choice_factory']);
            $choiceFactory = $this->formOptions['choice_factory'];
            if (array_key_exists('factory', $choiceFactory)) {
                Assert::classExists($choiceFactory['factory']);
                Assert::nullOrIsArray($choiceFactory['params']);
            }
        }

        $this->constraints = Dto\Constraint::serializeArray($constraints);
    }

    public function getQuestionKey(): string
    {
        return $this->questionKey;
    }

    public function getExpectedType(): string
    {
        return $this->expectedType;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getConstraints(): array
    {
        return $this->constraints;
    }

    public function isForceSet(): bool
    {
        return $this->forceSet;
    }

    public function getDependsOn(): array
    {
        return $this->dependsOn;
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

    public function getFormType(): mixed
    {
        return $this->formType;
    }

    public function setFormType(mixed $formType): self
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

    public function getAdditionalData(): ?array
    {
        return $this->additionalData;
    }

    public function setAdditionalData(?array $additionalData): self
    {
        $this->additionalData = $additionalData;
        return $this;
    }

    public function getInsertParams(): array
    {
        return [
            'question_key' => $this->getQuestionKey(),
            'expected_type' => $this->getExpectedType(),
            'constraints' => json_encode($this->getConstraints()),
            'is_nullable' => (int)$this->isNullable(),
            'label' => $this->getLabel(),
            'force_set' => (int)$this->isForceSet(),
            'depends_on' => json_encode($this->getDependsOn()),
            'sort_order' => 0,
            'default_value' => $this->getDefaultValue(),
            'form_type' => $this->getFormType(),
            'form_options' => json_encode($this->getFormOptions())
        ];
    }
}
