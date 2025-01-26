<?php

namespace Tests\ShortUrl;

use Fig\Http\Message\StatusCodeInterface;
use Tests\TestCase;

class RedirectShortUrlActionTest extends TestCase
{
    public function testShouldReturnRedirectResponse(): void
    {
        $url = 'https://google.com';
        [$response] = $this->jsonResponse('POST', '/api/shorts', ['origin' => $url]);
        [, , ,$code] = explode('/', $response->redirectUrl);
        $responseRedirect = $this->request('GET', '/' . $code);

        $this->assertSame(StatusCodeInterface::STATUS_MOVED_PERMANENTLY, $responseRedirect->getStatusCode());
        $this->assertSame($url, $responseRedirect->getHeader('Location')[0]);
    }
}
