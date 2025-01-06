<?php

namespace App\Contract\Patterns\Factory;

use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class FactoryResolver
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    /**
     * Retrieves a factory from the container and ensures it implements the correct interface
     *
     * @template T of InvokableFactory|ParametrizedFactory
     * @param class-string<T> $factoryClass
     * @return T
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function resolveFactory(string $factoryClass): InvokableFactory|ParametrizedFactory
    {
        if (!class_exists($factoryClass)) {
            throw new InvalidArgumentException(sprintf(
                'Factory class "%s" does not exist',
                $factoryClass
            ));
        }

        $factory = $this->container->get($factoryClass);

        if (!$factory instanceof BaseFactory) {
            throw new InvalidArgumentException(sprintf(
                'Factory class "%s" must implement "%s"',
                $factoryClass,
                BaseFactory::class
            ));
        }

        return $factory;
    }
}
