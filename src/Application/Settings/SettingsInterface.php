<?php

declare(strict_types=1);

namespace Rodrifarias\ShortUrl\Application\Settings;

interface SettingsInterface
{
    public function get(string $key): mixed;
}
