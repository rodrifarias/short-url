<?php

namespace Tests\ShortUrl;

use Tests\TestCase;

class ErrorActionTest extends TestCase
{
    public function testShouldSeeErrorPage(): void
    {
        $response = $this->request('GET', '/page-not-found');
        $this->assertStringContainsString('<p>A error occurred.</p>', $response->getBody());
        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('text/html; charset=UTF-8', $response->getHeader('Content-Type')[0]);
    }

    public function testShouldSeeJsonError(): void
    {
        [$response, $status, $headers] = $this->jsonResponse('GET', '/api');
        $this->assertSame(404, $status);
        $this->assertSame('application/json', $headers['Content-Type'][0]);
        $this->assertSame(['code' => 404, 'message' => 'A error occurred.'], (array)$response);
    }
}
