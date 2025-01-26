<?php

declare(strict_types=1);

namespace Rodrifarias\ShortUrl\Domain\ShortUrl;

use DateTime;

interface ShortUrlRepositoryInterface
{
    /**
     * @param array<int,mixed> $condition
     * @return UrlOutput|null
     */
    public function find(array $condition): ?UrlOutput;
    public function create(string $origin, string $redirectUrl, DateTime $createdAt): void;
}
