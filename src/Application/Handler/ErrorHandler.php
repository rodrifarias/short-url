<?php

namespace Rodrifarias\ShortUrl\Application\Handler;

use Psr\Http\Message\ResponseInterface;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Slim\Views\Twig;

class ErrorHandler extends SlimErrorHandler
{
    protected function respond(): ResponseInterface
    {
        $this->logger->info($this->exception->getMessage());
        $isApiRequest = str_starts_with($this->request->getUri()->getPath(), '/api');
        $response = $this->responseFactory->createResponse()->withStatus($this->exception->getCode());

        if ($isApiRequest) {
            $response = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode([
                'code' => $this->exception->getCode(),
                'message' => $this->displayErrorDetails ? $this->exception->getMessage() : 'A error occurred.',
            ], JSON_THROW_ON_ERROR));
            return $response;
        }

        $response = $response->withHeader('Content-Type', 'text/html; charset=UTF-8');
        $twigT = Twig::create(__DIR__ . '/../../../src/Views', ['cache' => false]);
        return $twigT->render($response, 'error.twig');
    }
}
