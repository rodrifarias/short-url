<?php

namespace Rodrifarias\ShortUrl\Domain\ShortUrl;


use InvalidArgumentException;

readonly class UrlOutput
{
    public function __construct(
        public string $origin,
        public string $redirectUrl,
    ) {
        if (! filter_var($this->origin, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid Url');
        }
    }
}
