<?php

namespace App\Entity\Dto;

class Constraint
{
    private const string NO_PARAMS = 'no_params';

    public static function serializeArray(array $constraints): array
    {
        return array_map(fn($x) => self::serialize($x), $constraints);
    }

    public static function serialize(array $constraint): array
    {
        if (!in_array(count($constraint), [1, 2])) {
            throw new \InvalidArgumentException('Constraint class is missing');
        }

        if (array_key_exists('class', $constraint)) {
            $type = $constraint['class'];
        } else {
            $type = $constraint[0];
        }
        if (!class_exists($type)) {
            throw new \InvalidArgumentException("Class $type does not exist");
        }

        if (array_key_exists('options', $constraint)) {
            $options = $constraint['options'];
        } elseif (count($constraint) === 2) {
            $options = $constraint[1];
        } else {
            $options = self::NO_PARAMS;
        }

        return [
            'class' => $type,
            'options' => $options,
        ];
    }

    public static function unserializeArray(array $constraints): array
    {
        return array_map(fn(array $x) => self::unserialize($x), $constraints);
    }

    public static function unserialize(array $constraint): mixed
    {
        if (!array_key_exists('class', $constraint)) {
            throw new \InvalidArgumentException('Constraint class is missing');
        }

        $class = $constraint['class'];
        if (array_key_exists('options', $constraint) && $constraint['options'] !== self::NO_PARAMS) {
            $options = $constraint['options'];
        } else {
            $options = self::NO_PARAMS;
        }
        if (!class_exists($class)) {
            throw new \InvalidArgumentException("Class $class does not exist");
        }

        try {
            if ($options !== self::NO_PARAMS) {
                return new $class($options);
            } else {
                return new $class();
            }
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException("Class $class does not accept the provided options");
        }
    }
}
