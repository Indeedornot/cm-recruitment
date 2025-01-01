<?php

namespace App\Migration\Dto;


use App\Entity\Dto;

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
    ) {
        if ($this->label === null) {
            $this->label = 'components.question.' . $this->questionKey;
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
}
