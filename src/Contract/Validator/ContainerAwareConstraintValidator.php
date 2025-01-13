<?php

namespace App\Contract\Validator;

use Psr\Container\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\CallbackValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ContainerAwareConstraintValidator extends CallbackValidator
{

    public function __construct(private readonly ContainerInterface $container)
    {
    }

    public function validate(mixed $object, Constraint $constraint): void
    {
        if (!$constraint instanceof Callback) {
            throw new UnexpectedTypeException($constraint, Callback::class);
        }

        $method = $constraint->callback;
        if ($method instanceof \Closure) {
            $method($object, $this->context, $constraint->payload);
        } elseif (\is_array($method)) {
            if (!\is_callable($method)) {
                if (isset($method[0]) && \is_object($method[0])) {
                    $method[0] = $method[0]::class;
                }
                throw new ConstraintDefinitionException(json_encode($method) . ' targeted by Callback constraint is not a valid callable.');
            }

            $method($object, $this->context, $constraint->payload, $this->container);
        } elseif (null !== $object) {
            if (!method_exists($object, $method)) {
                throw new ConstraintDefinitionException(sprintf('Method "%s" targeted by Callback constraint does not exist in class "%s".',
                    $method, get_debug_type($object)));
            }

            $reflMethod = new \ReflectionMethod($object, $method);

            if ($reflMethod->isStatic()) {
                $reflMethod->invoke(null, $object, $this->context, $constraint->payload, $this->container);
            } else {
                $reflMethod->invoke($object, $this->context, $constraint->payload, $this->container);
            }
        }
    }
}
