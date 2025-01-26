<?php

declare(strict_types=1);

namespace Rodrifarias\ShortUrl\Domain\ShortUrl;

use InvalidArgumentException;
use Throwable;

class InvalidUrlException extends InvalidArgumentException
{
    public function __construct(string $message = 'Invalid Url', int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
