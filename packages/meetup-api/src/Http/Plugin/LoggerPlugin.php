<?php

declare(strict_types=1);

namespace Meetup\Http\Plugin;

use Http\Client\Common\Plugin;
use Http\Client\Exception;
use Http\Message\Formatter;
use Http\Message\Formatter\SimpleFormatter;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

final class LoggerPlugin implements Plugin
{
    /** @var LoggerInterface */
    private $logger;
    /** @var Formatter */
    private $formatter;

    public function __construct(LoggerInterface $logger, Formatter $formatter = null)
    {
        $this->logger = $logger;
        $this->formatter = $formatter ?: new SimpleFormatter();
    }

    public function handleRequest(RequestInterface $request, callable $next, callable $first) : Promise
    {
        $this->logger->debug(
            sprintf('Emit request: "%s"', $this->formatter->formatRequest($request)),
            ['request' => $request]
        );

        return $next($request)->then(function (ResponseInterface $response) use ($request) {
            $this->logger->info(
                sprintf('Receive response: "%s" for request: "%s"',
                    $this->formatter->formatResponse($response),
                    $this->formatter->formatRequest($request)
                ),
                $this->logContext($request, $response)
            );

            return $response;
        }, function (Exception $exception) use ($request) {
            if ($exception instanceof Exception\HttpException) {
                $this->logger->error(
                    sprintf('Error: "%s" with response: "%s" when emitting request: "%s"',
                        $exception->getMessage(),
                        $this->formatter->formatResponse($exception->getResponse()),
                        $this->formatter->formatRequest($request)
                    ),
                    $this->logContext($request, $exception->getResponse())
                );
            } else {
                $this->logger->error(
                    sprintf('Error: "%s" when emitting request: "%s"',
                        $exception->getMessage(),
                        $this->formatter->formatRequest($request)
                    ),
                    $this->logContext($request)
                );
            }

            throw $exception;
        });
    }

    private function logContext(RequestInterface $request, ResponseInterface $response = null) : array
    {
        if (null === $response) {
            return [
                'request' => $request->getHeaders(),
            ];
        }

        $headers = $response->getHeaders();

        return [
            'request' => $request->getHeaders(),
            'response' => [
                'StatusCode' => $response->getStatusCode(),
                'X-Meetup-server' => $headers['X-Meetup-server'] ?? null,
                'X-Total-Count' => $headers['X-Total-Count'] ?? null,
                'X-Meetup-Request-ID' => $headers['X-Meetup-Request-ID'],
                'X-RateLimit-Limit' => $headers['X-RateLimit-Limit'],
                'X-RateLimit-Remaining' => $headers['X-RateLimit-Remaining'],
                'X-RateLimit-Reset' => $headers['X-RateLimit-Reset'],
            ],
        ];
    }
}
