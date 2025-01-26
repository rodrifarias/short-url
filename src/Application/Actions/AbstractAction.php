<?php

declare(strict_types=1);

namespace Rodrifarias\ShortUrl\Application\Actions;

use Exception;
use Fig\Http\Message\StatusCodeInterface;
use JsonException;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\{ServerRequestInterface as Request, ResponseInterface as Response, ResponseInterface};
use Slim\Exception\HttpBadRequestException;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractAction
{
    protected Request $request;

    protected Response $response;

    /** @var array<string, string> */
    protected array $args;

    public function __construct(protected LoggerInterface $logger)
    {
    }

    /**
     * @param Request $request
     * @param ResponseInterface $response
     * @param array<string,string> $args
     * @return ResponseInterface
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        return $this->action();
    }

    abstract protected function action(): Response;

    /**
     * @throws Exception
     */
    protected function resolveArg(string $name): string
    {
        return $this->args[$name] ?? throw new Exception('Could not resolve argument ' . $name, StatusCodeInterface::STATUS_BAD_REQUEST);
    }

    protected function input(string $name): string|int|bool|null
    {
        $body = (array) ($this->request->getParsedBody() ?: json_decode($this->request->getBody()->getContents()));
        return $body[$name] ?? throw new HttpBadRequestException($this->request, 'Could not resolve argument '. $name);
    }

    /**
     * @throws JsonException
     */
    protected function jsonResponse(mixed $data, int $status = StatusCodeInterface::STATUS_OK): ResponseInterface
    {
        $response = $this->response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($status);
        $response->getBody()->write(json_encode($data, JSON_THROW_ON_ERROR));
        return $response;
    }

    /**
     * @param string $view
     * @param array<string,mixed> $data
     * @return ResponseInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function render(string $view, array $data = []): ResponseInterface
    {
        $response = $this->response->withHeader('Content-Type', 'text/html; charset=UTF-8');
        return Twig::fromRequest($this->request)->render($response, $view . '.twig', $data);
    }

}
