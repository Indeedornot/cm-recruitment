<?php

namespace App\Migration\Dto;

use App\Entity\Dto;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GlobalConfigDto
{
    public function __construct(
        private string $key,
        private mixed $defaultValue = null,
        private string $formType = TextType::class,
        private array $formOptions = [],
        private array $constraints = [],
        private ?string $label = null,
    ) {
        if ($this->label === null) {
            $this->label = 'components.global_config.' . $this->key;
        } elseif (str_starts_with($this->label, '.')) {
            $this->label = 'components.global_config.' . $this->key . $this->label;
        }

        $this->constraints = Dto\Constraint::serializeArray($constraints);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    public function getFormType(): string
    {
        return $this->formType;
    }

    public function getFormOptions(): array
    {
        return $this->formOptions;
    }

    public function getConstraints(): array
    {
        return $this->constraints;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getInsertParams(): array
    {
        return [
            'key' => $this->getKey(),
            'label' => $this->getLabel(),
            'value' => json_encode($this->getDefaultValue()),
            'form_type' => $this->getFormType(),
            'form_options' => json_encode($this->getFormOptions()),
            'constraints' => json_encode(Dto\Constraint::serializeArray($this->getConstraints())),
        ];
    }
}
