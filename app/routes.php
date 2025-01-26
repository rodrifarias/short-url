<?php

declare(strict_types=1);

use Rodrifarias\ShortUrl\Application\Actions\ShortUrl\{CreateShortUrlAction, RedirectShortUrlAction, ViewShortUrlPageAction};
use Slim\App;

return function (App $app) {
    $app->get('/', ViewShortUrlPageAction::class)->setName('short-url-page');
    $app->get('/{code:[A-Z0-9]+}', RedirectShortUrlAction::class)->setName('short-url-redirect');
    $app->post('/api/shorts', CreateShortUrlAction::class)->setName('short-url-api-create');
};
