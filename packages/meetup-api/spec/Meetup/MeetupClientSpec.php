<?php

namespace spec\Meetup;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Meetup\MeetupClient;
use Meetup\Resource\Events;
use Meetup\Resource\Groups;
use PhpSpec\ObjectBehavior;
use Zend\Hydrator\HydratorInterface;

class MeetupClientSpec extends ObjectBehavior
{
    function let(
        HttpClient $httpClient,
        MessageFactory $messageFactory,
        HydratorInterface $hydrator
    ) {
        $this->beConstructedWith($httpClient, $messageFactory, $hydrator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MeetupClient::class);
    }

    function it_should_have_http_client()
    {
        $this->getHttpClient()->shouldBeAnInstanceOf(HttpMethodsClient::class);
    }

    function it_should_have_events_api()
    {
        $this->events()->shouldBeAnInstanceOf(Events::class);
    }

    function it_should_have_groups_api()
    {
        $this->groups()->shouldBeAnInstanceOf(Groups::class);
    }
}
