<?php

namespace App\Contract\Validator;

use Attribute;
use Symfony\Component\Validator\Constraints\Callback;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class ContainerAwareConstraint extends Callback
{
    public function __construct(
        array|string|callable|null $callback = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = []
    ) {
        parent::__construct($callback, $groups, $payload, $options);
    }
}
