<?php

declare(strict_types=1);

namespace Rodrifarias\ShortUrl\Application\Actions\ShortUrl;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;

class RedirectShortUrlAction extends ShortUrlAction
{
    protected function action(): Response
    {
        $code = $this->resolveArg('code');
        $url = $this->findShortUrl->execute($code);
        $response = $this->response->withHeader('Location', $url->origin);
        return $response->withStatus(StatusCodeInterface::STATUS_MOVED_PERMANENTLY);
    }
}
