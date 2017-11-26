<?php

declare(strict_types=1);

namespace Meetup\Http\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Meetup\Http\RateLimitExceeded;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class RateLimitExceededPlugin implements Plugin
{
    public function handleRequest(RequestInterface $request, callable $next, callable $first) : Promise
    {
        /** @var Promise $promise */
        $promise = $next($request);

        return $promise->then(function (ResponseInterface $response) use ($request) {
            if (429 === $response->getStatusCode()) {
                throw new RateLimitExceeded($request, $response);
            }

            return $response;
        });
    }
}
