<?php

namespace spec\Meetup\Http\Plugin;

use Http\Client\Common\Plugin;
use Http\Client\Promise\HttpFulfilledPromise;
use Http\Promise\FulfilledPromise;
use Meetup\Http\Plugin\AddApiKeyPlugin;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class AddApiKeyPluginSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('secret_key');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AddApiKeyPlugin::class);
    }

    function it_is_a_plugin()
    {
        $this->shouldImplement(Plugin::class);
    }

    function it_should_add_api_key_to_query_string(
        RequestInterface $request,
        ResponseInterface $response,
        UriInterface $uri
    ) {
        $request->getUri()->shouldBeCalled()->willReturn($uri);
        $uri->getQuery()->shouldBeCalled()->willReturn('');
        $uri->withQuery('key=secret_key')->shouldBeCalled()->willReturn($uri);
        $request->withUri($uri)->shouldBeCalled()->willReturn($request);

        $next = function (RequestInterface $receivedRequest) use($request, $response) {
            if (Argument::is($request->getWrappedObject())->scoreArgument($receivedRequest)) {
                return new HttpFulfilledPromise($response->getWrappedObject());
            }
        };

        $promise = $this->handleRequest($request, $next, function () {});
        $promise->shouldReturnAnInstanceOf(HttpFulfilledPromise::class);
    }

    function it_should_add_api_key_to_existing_query_string(
        RequestInterface $request,
        ResponseInterface $response,
        UriInterface $uri
    ) {
        $request->getUri()->shouldBeCalled()->willReturn($uri);
        $uri->getQuery()->shouldBeCalled()->willReturn('query=lorem');
        $uri->withQuery('query=lorem&key=secret_key')->shouldBeCalled()->willReturn($uri);
        $request->withUri($uri)->shouldBeCalled()->willReturn($request);

        $next = function (RequestInterface $receivedRequest) use($request, $response) {
            if (Argument::is($request->getWrappedObject())->scoreArgument($receivedRequest)) {
                return new HttpFulfilledPromise($response->getWrappedObject());
            }
        };

        $promise = $this->handleRequest($request, $next, function () {});
        $promise->shouldReturnAnInstanceOf(HttpFulfilledPromise::class);
    }
}
