<?php

namespace App\Migration\Dto;

use App\Entity\Dto;
use phpDocumentor\Reflection\Types\ClassString;
use Symfony\Component\Form\AbstractType;

class CopyTextDto
{
    /**
     * @param string $text
     * @param array $constraints
     * @param bool $required
     * @param class-string<AbstractType> $formType
     * @param array $formOptions
     * @param string|null $label
     */
    public function __construct(
        public string $text,
        public array $constraints,
        public bool $required,
        public string $formType,
        public array $formOptions = [],
        public mixed $defaultValue = null,
        public ?string $label = null,
    ) {
        $this->constraints = Dto\Constraint::serializeArray($constraints);
        if (!empty($label) && str_starts_with('.', $label)) {
            $this->label = 'components.posting.copytext' . $label;
        } else {
            $this->label = $label ?? 'components.posting.copytext.' . $text;
        }

        if (!class_exists($formType) || !is_subclass_of($formType, AbstractType::class)) {
            throw new \InvalidArgumentException('Invalid form type');
        }
    }
}
