<?php

namespace App\Contract\Patterns\Factory;

use LogicException;

/**
 * @template T
 */
interface ParametrizedFactory extends BaseFactory
{
    /**
     * @param array $params
     * @return T
     * @throws LogicException
     */
    public function __invoke(array $params);
}
