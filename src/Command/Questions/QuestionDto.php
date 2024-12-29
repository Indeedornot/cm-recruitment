<?php

namespace App\Command\Questions;

use App\Entity\Question;
use Symfony\Component\Validator\Constraint;

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

    public function fillQuestion(Question $question): Question
    {
        $question->setQuestionKey($this->questionKey);
        $question->setExpectedType($this->expectedType);
        $question->setDefaultValue($this->defaultValue);
        $question->setIsNullable($this->isNullable);
        $question->setLabel($this->label);
        $question->setConstraints(self::serializeConstraint($this->constraints));
        $question->setForceSet($this->forceSet);
        $question->setDependsOn($this->dependsOn);
        $question->setSortOrder($this->sortOrder);

        return $question;
    }

    /** @param array{0: class-string, 1: mixed}[] $constraint */
    public static function serializeConstraint(Constraint|array $constraint): array
    {
        return array_map(function ($constraint) {
            $data = [];
            $data['class'] = $constraint[0];
            if (array_key_exists(1, $constraint)) {
                $data['options'] = $constraint[1];
            }
            return $data;
        }, $constraint);
    }
}
