<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Rodrifarias\ShortUrl\Application\Settings\SettingsInterface;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$settings = require __DIR__ . '/app/settings.php';
$repositories = require __DIR__ . '/app/repositories.php';

$containerBuilder = new ContainerBuilder();
$settings($containerBuilder);
$repositories($containerBuilder);
$container = $containerBuilder->build();
$pdo = $container->get(PDO::class);
$env = $container->get(SettingsInterface::class)->get('app_env');

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => $env,
        'production' => [
            'name' => 'production_db',
            'connection' => $pdo,
        ],
        'development' => [
            'name' => 'development_db',
            'connection' => $pdo,
        ],
    ],
    'version_order' => 'creation'
];
