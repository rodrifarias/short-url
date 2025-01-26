<?php

declare(strict_types=1);

namespace Rodrifarias\ShortUrl\Application\Actions\ShortUrl;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Rodrifarias\ShortUrl\Domain\ShortUrl\UrlOriginInput;

class CreateShortUrlAction extends ShortUrlAction
{
    protected function action(): ResponseInterface
    {
        $origin = (string) $this->input('origin');
        $urlOutput = $this->createShortUrl->execute(new UrlOriginInput($origin));
        return $this->jsonResponse($urlOutput, StatusCodeInterface::STATUS_CREATED);
    }
}
