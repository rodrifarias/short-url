<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;
use Rodrifarias\ShortUrl\Domain\ShortUrl\CodeGenerator;

class UrlsSeeder extends AbstractSeed
{
    public function run(): void
    {
        $fac = fn (string $origin) => [
            'origin' => $origin,
            'redirect_url' => $_ENV['APP_URL'] . '/' . CodeGenerator::create(),
            'created_at' => new DateTime()->format('Y-m-d H:i:s'),
        ];

        $data = [
            $fac('https://symfony.com'),
            $fac('https://laravel.com'),
            $fac('https://www.php.net'),
            $fac('https://www.php-fig.org'),
            $fac('https://phptherightway.com'),
        ];

        $this->table('urls')->insert($data)->saveData();
    }
}
