<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

class HelpersExtension extends AbstractExtension
{
    public function getTests()
    {
        return [
            new TwigTest('instanceof', [$this, 'isInstanceof'])
        ];
    }

    /**
     * @param $var
     * @param class-string $instance
     * @return bool
     */
    public function isInstanceof($var, string $instance)
    {
        return $var instanceof $instance;
    }
}
