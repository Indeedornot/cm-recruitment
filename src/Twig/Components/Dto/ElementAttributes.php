<?php

namespace App\Twig\Components\Dto;

use App\Services\Assets\ScriptCollection;
use App\Services\Assets\StyleCollection;

class ElementAttributes
{
    public function __construct(
        public array $attributes = [],
        public bool $merge = true
    ) {
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function all(): array
    {
        return $this->attributes;
    }

    public function addOrSet(array $attributes): static
    {
        if ($this->merge) {
            $this->attributes = array_merge($this->attributes, $attributes);
        } else {
            $this->attributes = $attributes;
        }

        return $this;
    }

    public function append(string $key, mixed $value, bool $trim = true): static
    {
        $this->attributes[$key] ??= '';
        $this->attributes[$key] .= $value;
        if ($trim) {
            $this->attributes[$key] = trim($this->attributes[$key]);
        }
        return $this;
    }

    public function add(string $key, mixed $value): static
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function remove(string $key): static
    {
        unset($this->attributes[$key]);
        return $this;
    }

    public function set(string $key, mixed $value): static
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
    }

    public function __toString(): string
    {
        return $this->getHtmlAttributes();
    }

    public function getHtmlAttributes(): string
    {
        $htmlAttributes = '';
        foreach ($this->attributes as $key => $value) {
            $htmlAttributes .= "$key=\"$value\"";
            ScriptCollection::Log($key, $value);
            $htmlAttributes .= ' ';
        }
        ScriptCollection::Log($htmlAttributes);
        return $htmlAttributes;
    }
}
