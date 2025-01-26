<?php

namespace Rodrifarias\ShortUrl\Domain\ShortUrl;

class FindShortUrl
{
    public function __construct(private ShortUrlRepositoryInterface $repository)
    {
    }

    public function execute(string $code): UrlOutput
    {
        $url = $this->repository->find(['redirect_url', 'LIKE', "%$code%"]);
        return $url ?? throw new ShortUrlNotFoundException();
    }
}
