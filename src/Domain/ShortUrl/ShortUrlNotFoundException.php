<?php

declare(strict_types=1);

namespace Rodrifarias\ShortUrl\Domain\ShortUrl;

use Exception;
use Throwable;

class ShortUrlNotFoundException extends Exception
{
    public function __construct(string $message = 'ShortUrl not found', int $code = 404, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
