<?php

namespace App\Services\Assets;

use App\Helpers\Singleton;

class StyleCollection extends Singleton
{
    /** @var array<string, string> */
    private array $styles = [];

    public function addStyle(string $script, ?string $id = null): void
    {
        if ($id === null) {
            $this->styles[] = $script;
        } elseif (!array_key_exists($id, $this->styles)) {
            $this->styles[$id] = $script;
        }
    }

    public function getStyles(): array
    {
        return $this->styles;
    }

    public function getHtmlStyles(): string
    {
        return implode("\n", $this->styles);
    }
}

