<?php

namespace spec\Meetup;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Meetup\Meetup;
use Meetup\Resource\Events;
use PhpSpec\ObjectBehavior;

class MeetupSpec extends ObjectBehavior
{
    function let(
        HttpClient $httpClient,
        MessageFactory $messageFactory
    ) {
        $this->beConstructedWith($httpClient, $messageFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Meetup::class);
    }

    function it_should_have_http_client()
    {
        $this->getHttpClient()->shouldBeAnInstanceOf(HttpMethodsClient::class);
    }

    function it_should_have_events_api()
    {
        $this->events()->shouldBeAnInstanceOf(Events::class);
    }
}
