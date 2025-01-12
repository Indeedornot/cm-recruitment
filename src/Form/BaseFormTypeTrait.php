<?php

namespace App\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Webmozart\Assert\Assert;

/**
 * @template T
 */
trait BaseFormTypeTrait
{
    /**
     * @return T
     */
    protected function getData(FormBuilderInterface|FormEvent|FormInterface $builder)
    {
        return $builder->getData();
    }


    /**
     * @template TClass
     * @param class-string<TClass>|null $class
     * @return TClass
     */
    protected function getIData(FormBuilderInterface|FormEvent|FormInterface $builder, ?string $class = null)
    {
        $data = $this->getData($builder);
        match ($class) {
            'string' => Assert::string($data),
            'int' => Assert::integer($data),
            'float' => Assert::float($data),
            null => '',
            default => Assert::isInstanceOf($data, $class),
        };

        return $data;
    }
}
