<?php

namespace App\Services\Navigation;

class NavRoute
{
    public function __construct(
        private string $key,
        private string $label,
        private bool   $enabled,
        private string $href,
        private bool   $isCurrent
    )
    {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function getRouteKey(): string
    {
        return $this->key;
    }

    public function isCurrent(): bool
    {
        return $this->isCurrent;
    }
}
