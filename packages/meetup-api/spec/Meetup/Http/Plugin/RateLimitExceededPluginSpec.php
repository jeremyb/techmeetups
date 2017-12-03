<?php

declare(strict_types=1);

namespace spec\Meetup\Http\Plugin;

use Http\Client\Common\Plugin;
use Http\Client\Promise\HttpFulfilledPromise;
use Http\Client\Promise\HttpRejectedPromise;
use Meetup\Http\Plugin\RateLimitExceededPlugin;
use Meetup\Http\RateLimitExceeded;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class RateLimitExceededPluginSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beAnInstanceOf(RateLimitExceededPlugin::class);
    }

    function it_is_a_plugin()
    {
        $this->shouldImplement(Plugin::class);
    }

    function it_throws_rate_limit_exceeded_exception_on_429_error(
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $response->getStatusCode()->shouldBeCalled()->willReturn(429);
        $response->getReasonPhrase()->willReturn('Too Many Requests');

        $next = function (RequestInterface $receivedRequest) use($request, $response) {
            if (Argument::is($request->getWrappedObject())->scoreArgument($receivedRequest)) {
                return new HttpFulfilledPromise($response->getWrappedObject());
            }
        };
        $promise = $this->handleRequest($request, $next, function () {});
        $promise->shouldReturnAnInstanceOf(HttpRejectedPromise::class);
        $promise->shouldThrow(RateLimitExceeded::class)->duringWait();
    }
}
