<?php

declare(strict_types=1);

namespace Rodrifarias\ShortUrl\Application\Settings;

use Exception;

class Settings implements SettingsInterface
{
    /**
     * @param array<string,mixed> $settings
     */
    public function __construct(private array $settings)
    {
    }

    public function get(string $key): mixed
    {
        return $this->settings[$key] ?? throw new Exception("Setting '{$key}' not found");
    }
}
