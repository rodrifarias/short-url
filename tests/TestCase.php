<?php

declare(strict_types=1);

namespace Tests;

use DI\ContainerBuilder;
use PDO;
use Phinx\Config\Config;
use Phinx\Migration\Manager;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\NullLogger;
use Rodrifarias\ShortUrl\Application\Handler\ErrorHandler;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\{Factory\StreamFactory, Headers, Request as SlimRequest, Uri};
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

class TestCase extends PHPUnitTestCase
{
    protected App $app;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app = $this->getAppInstance();
    }

    protected function runMigrations(App $slimApp): void
    {
        $pdo = $slimApp->getContainer()->get(PDO::class);
        $config = [
            'paths' => [
                'migrations' => __DIR__ . '/../db/migrations',
                'seeds' => __DIR__ . '/../db/seeds',
            ],
            'environments' => [
                'testing' => [
                    'adapter' => 'sqlite',
                    'name' => 'testing_db',
                    'connection' => $pdo,
                ],
            ],
            'version_order' => 'creation'
        ];

        $config = new Config($config);
        $manager = new Manager($config, new StringInput(' '), new NullOutput());
        $manager->migrate('testing');
    }

    protected function getAppInstance(): App
    {
        $containerBuilder = new ContainerBuilder();

        $settings = require __DIR__ . '/../app/settings.php';
        $dependencies = require __DIR__ . '/../app/dependencies.php';
        $repositories = require __DIR__ . '/../app/repositories.php';
        $routes = require __DIR__ . '/../app/routes.php';

        $settings($containerBuilder);
        $dependencies($containerBuilder);
        $repositories($containerBuilder);

        $container = $containerBuilder->build();
        $twig = Twig::create(__DIR__ . '/../src/Views', ['cache' => false]);

        AppFactory::setContainer($container);
        $app = AppFactory::create();
        $app->add(TwigMiddleware::create($app, $twig));

        $errorHandler = new ErrorHandler($app->getCallableResolver(), $app->getResponseFactory(), new NullLogger());
        $errorMiddleware = $app->addErrorMiddleware(false, false, false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);

        $routes($app);

        return $app;
    }

    protected function request(
        string $method,
        string $path,
        array $body = [],
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): ResponseInterface {
        $uri = new Uri('', '', 8080, $path);
        $handle = fopen('php://temp', 'w+');
        $stream = new StreamFactory()->createStreamFromResource($handle);
        $request = new SlimRequest($method, $uri, new Headers($headers), $cookies, $serverParams, $stream);
        $request = $request->withParsedBody($body);
        $this->runMigrations($this->app);
        return $this->app->handle($request);
    }

    protected function jsonResponse(
        string $method,
        string $path,
        array $body = [],
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): mixed {
        $response = $this->request($method, $path, $body, $headers, $cookies, $serverParams);
        $response->getBody()->rewind();
        return [
            json_decode($response->getBody()->getContents()),
            $response->getStatusCode(),
            $response->getHeaders(),
        ];
    }
}
