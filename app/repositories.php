<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Rodrifarias\ShortUrl\Domain\ShortUrl\ShortUrlRepositoryInterface;
use Rodrifarias\ShortUrl\Infra\Database\Repository\ShortUrlRepositoryDatabase;
use Rodrifarias\ShortUrl\Application\Settings\SettingsInterface;

use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        PDO::class => function (SettingsInterface $s): PDO {
            $databaseConfig = $s->get('database');
            $databaseDefault = $databaseConfig['default'];
            $connection = $databaseConfig['connections'][$databaseDefault];

            return match ($databaseDefault) {
                'mysql' => 'mysql',
                'sqlite' => new PDO($connection['driver'] . ':' . $connection['database']),
                default => throw new Exception('Database not defined: ' . $databaseDefault),
            };
        },
        ShortUrlRepositoryInterface::class => autowire(ShortUrlRepositoryDatabase::class),
    ]);
};
