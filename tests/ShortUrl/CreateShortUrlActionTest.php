<?php

namespace Tests\ShortUrl;

use Faker\Factory as Faker;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CreateShortUrlActionTest extends TestCase
{
    #[DataProvider('dataProviderUrl')]
    public function testShouldCreateNewShortUrl(string $url): void
    {
        [$response, $statusCode] = $this->jsonResponse('POST', '/api/shorts', ['origin' => $url]);

        $this->assertSame(StatusCodeInterface::STATUS_CREATED, $statusCode);
        $this->assertStringStartsWith($_ENV['APP_URL'], $response->redirectUrl);
        $this->assertTrue(mb_strlen($response->redirectUrl) >= (mb_strlen($_ENV['APP_URL']) + 4));
        $this->assertTrue((bool) preg_match('/[A-Z0-9$]/', $response->redirectUrl));
    }

    public static function dataProviderUrl(): array
    {
        $faker = Faker::create();
        $urls = [];

        for ($i = 0; $i < 100   ; $i++) {
            $urls['origin-' . $i] = [$faker->url()];
        }

        return $urls;
    }

    public function testShouldCreateSameCodeShortUrl(): void
    {
        $origin = 'https://google.com';
        [$response1] = $this->jsonResponse('POST', '/api/shorts', ['origin' => $origin]);
        [$response2] = $this->jsonResponse('POST', '/api/shorts', ['origin' => $origin]);

        $this->assertSame($response2->origin, $response1->origin);
        $this->assertSame($response2->redirectUrl, $response1->redirectUrl);
    }
}
