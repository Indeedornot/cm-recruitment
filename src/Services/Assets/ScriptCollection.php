<?php

namespace App\Services\Assets;

use App\Helpers\Singleton;

/**
 * @extends Singleton<ScriptCollection>
 */
class ScriptCollection extends Singleton
{
    /** @var array<string, string> */
    private array $scripts = [];

    public static function Log(...$vars): void
    {
        foreach ($vars as $var) {
            $val = $var;
            if (is_array($var) || is_object($var)) {
                $val = json_encode($var);
            }
            self::getInstance()->scripts[] = "<script>console.log('$val');</script>";
        }
    }

    public function addScript(string $script, ?string $id = null): void
    {
        if ($id === null) {
            $this->scripts[] = $script;
        } elseif (!array_key_exists($id, $this->scripts)) {
            $this->scripts[$id] = $script;
        }
    }

    public function getScripts(): array
    {
        return $this->scripts;
    }

    public function getHtmlScripts(): string
    {
        return implode("\n", $this->scripts);
    }
}
