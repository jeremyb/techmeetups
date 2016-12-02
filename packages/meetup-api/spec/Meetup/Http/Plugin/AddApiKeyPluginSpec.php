<?php

namespace spec\Meetup\Http\Plugin;

use Meetup\Http\Plugin\AddApiKeyPlugin;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\RequestInterface;
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

    function it_should_add_api_key_to_query_string(
        RequestInterface $request,
        UriInterface $uri
    ) {
        $request->getUri()->shouldBeCalled()->willReturn($uri);
        $uri->withQuery('key=secret_key')->shouldBeCalled()->willReturn($uri);
        $request->withUri($uri)->shouldBeCalled();

        $this->handleRequest($request, function () {}, function () {});
    }
}
