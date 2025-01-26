<?php

namespace Tests\ShortUrl;

use Tests\TestCase;

class ViewShortUrlPageActionTest extends TestCase
{
    public function testShouldSeeShorterUrlTitle(): void
    {
        $response = $this->request('GET', '/');
        $this->assertStringContainsString('<title>Short URL App</title>', $response->getBody());
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('text/html; charset=UTF-8', $response->getHeader('Content-Type')[0]);
    }
}
