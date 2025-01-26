<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Rodrifarias\ShortUrl\Application\Handler\ErrorHandler;
use Rodrifarias\ShortUrl\Application\Settings\SettingsInterface;
use Slim\Factory\AppFactory;
use Slim\Views\{Twig, TwigMiddleware};

require __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('America/Sao_Paulo');

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

$routes = require __DIR__ . '/../app/routes.php';
$dependencies = require __DIR__ . '/../app/dependencies.php';
$settings = require __DIR__ . '/../app/settings.php';
$repositories = require __DIR__ . '/../app/repositories.php';

$containerBuilder = new ContainerBuilder();
$twig = Twig::create(__DIR__ . '/../src/Views', ['cache' => false]);

$settings($containerBuilder);
$repositories($containerBuilder);
$dependencies($containerBuilder);

$container = $containerBuilder->build();

$app = AppFactory::createFromContainer($container);
$app->add(TwigMiddleware::create($app, $twig));
$routes($app);

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

$errorHandler = new ErrorHandler($app->getCallableResolver(), $app->getResponseFactory());

$isEnvDev = $container->get(SettingsInterface::class)->get('app_env') === 'development';

$errorMiddleware = $app->addErrorMiddleware($isEnvDev, $isEnvDev, $isEnvDev);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

$app->run();
