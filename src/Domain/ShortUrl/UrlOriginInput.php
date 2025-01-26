<?php

declare(strict_types=1);

namespace Rodrifarias\ShortUrl\Domain\ShortUrl;

readonly class UrlOriginInput
{
    public function __construct(public string $value)
    {
        if (! filter_var($this->value, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException();
        }
    }
}
