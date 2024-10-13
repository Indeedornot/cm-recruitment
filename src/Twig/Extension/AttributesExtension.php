<?php

namespace App\Twig\Extension;

use App\Twig\Components\Dto\ElementAttributes;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AttributesExtension extends AbstractExtension
{
    public function __construct()
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'attrs_object',
                [$this, 'getAttributes'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    public function getAttributes(array $attributes = [], bool $merge = true): ElementAttributes
    {
        return new ElementAttributes($attributes, $merge);
    }
}
