<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;
use Rodrifarias\ShortUrl\Application\Settings\{Settings, SettingsInterface};

return function (ContainerBuilder $containerBuilder) {
    $isEnvDev = $_ENV['APP_ENV'] === 'development';

    $containerBuilder->addDefinitions([
        SettingsInterface::class => new Settings([
            'app_env' => $_ENV['APP_ENV'],
            'app_url' => $_ENV['APP_URL'],
            'displayErrorDetails' => $isEnvDev,
            'logError' => $isEnvDev,
            'logErrorDetails' => $isEnvDev,
            'logger' => [
                'name' => $_ENV['APP_NAME'],
                'path' => 'php://stdout',
                'level' => Logger::INFO,
            ],
            'database' => [
                'default' => $_ENV['DB_CONNECTION'],
                'connections' => [
                    'sqlite' => [
                        'driver' => 'sqlite',
                        'database' => $_ENV['DB_DATABASE'] ?: __DIR__ . '/../db/database.sqlite',
                    ],
                    'mysql' => [],
                ]
            ]
        ]),
    ]);
};
