<?php

declare(strict_types=1);

namespace Rodrifarias\ShortUrl\Application\Actions\ShortUrl;

use Psr\Http\Message\ResponseInterface as Response;

class ViewShortUrlPageAction extends ShortUrlAction
{
    protected function action(): Response
    {
        return $this->render('short-url-page');
    }
}
