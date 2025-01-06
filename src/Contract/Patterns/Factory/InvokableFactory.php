<?php

namespace App\Contract\Patterns\Factory;

/**
 * @template T
 */
interface InvokableFactory extends BaseFactory
{
    /**
     * @return T
     */
    public function __invoke();
}
