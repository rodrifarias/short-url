<?php

declare(strict_types=1);

namespace Rodrifarias\ShortUrl\Application\Actions\ShortUrl;

use Psr\Log\LoggerInterface;
use Rodrifarias\ShortUrl\Application\Actions\AbstractAction;
use Rodrifarias\ShortUrl\Domain\ShortUrl\{CreateShortUrl, FindShortUrl};

abstract class ShortUrlAction extends AbstractAction
{
    public function __construct(
        protected LoggerInterface $logger,
        protected CreateShortUrl $createShortUrl,
        protected FindShortUrl $findShortUrl,
    ) {
        parent::__construct($this->logger);
    }
}
