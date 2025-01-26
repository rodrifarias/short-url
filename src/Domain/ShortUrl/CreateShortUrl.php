<?php

declare(strict_types=1);

namespace Rodrifarias\ShortUrl\Domain\ShortUrl;

use DateTime;
use Rodrifarias\ShortUrl\Application\Settings\SettingsInterface;

class CreateShortUrl
{
    public function __construct(
        private ShortUrlRepositoryInterface $repository,
        private SettingsInterface $settings,
    ) {
    }

    public function execute(UrlOriginInput $input, DateTime $createdAt = new DateTime()): UrlOutput
    {
        $url = $this->repository->find(['origin', '=', $input->value]);

        if ($url) {
            return $url;
        }

        $host = $this->settings->get('app_url');
        $redirectUrl = $host . '/' . CodeGenerator::create();
        $this->repository->create($input->value, $redirectUrl, $createdAt);

        return new UrlOutput($input->value, $redirectUrl);
    }
}
