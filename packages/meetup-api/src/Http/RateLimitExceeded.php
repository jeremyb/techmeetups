<?php

declare(strict_types=1);

namespace Meetup\Http;

use Http\Client\Exception\HttpException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class RateLimitExceeded extends HttpException
{
    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        \Exception $previous = null
    ) {
        $message = sprintf(
            '[url] %s [http method] %s [status code] %s [reason phrase] %s',
            $request->getRequestTarget(),
            $request->getMethod(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        );

        parent::__construct($message, $request, $response, $previous);
    }

    public function getRateLimit() : int
    {
        return (int) $this->response->getHeader('X-RateLimit-Limit');
    }

    public function getRateLimitRemaining() : int
    {
        return (int) $this->response->getHeader('X-RateLimit-Remaining');
    }

    public function getRateLimitReset() : int
    {
        return (int) $this->response->getHeader('X-RateLimit-Reset');
    }
}
