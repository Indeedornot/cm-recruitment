<?php

namespace App\Contract\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class PeselConstraint extends Constraint
{
    public string $message = 'validators.pesel.invalid';
    public array $options = [];

    public function __construct(?string $message = null, array $options = [], ?array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->message = $message ?? $this->message;
        $this->options = $options;
    }
}
