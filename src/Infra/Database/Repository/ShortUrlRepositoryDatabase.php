<?php

declare(strict_types=1);

namespace Rodrifarias\ShortUrl\Infra\Database\Repository;

use DateTime;
use Rodrifarias\ShortUrl\Domain\ShortUrl\{ShortUrlRepositoryInterface, UrlOutput};

class ShortUrlRepositoryDatabase extends BaseRepository implements ShortUrlRepositoryInterface
{
    /** @param array<int,string> $condition  */
    public function find(array $condition): ?UrlOutput
    {
        $result = $this->findOneBy('urls', '*', $condition);
        return is_array($result) ? new UrlOutput((string) $result['origin'], (string) $result['redirect_url']) : null;
    }

    public function create(string $origin, string $redirectUrl, DateTime $createdAt): void
    {
        $this->insert('urls', [
            'origin' => $origin,
            'redirect_url' => $redirectUrl,
            'created_at' => $createdAt->format('Y-m-d H:i:s'),
        ]);
    }
}
