<?php

namespace spec\Meetup\Resource;

use Http\Client\Common\HttpMethodsClient;
use Meetup\DTO\Event;
use Meetup\Resource\Events;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class EventsSpec extends ObjectBehavior
{
    function let(HttpMethodsClient $httpClient)
    {
        $this->beConstructedWith($httpClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Events::class);
    }

    function it_should_get_events_of_group(
        HttpMethodsClient $httpClient,
        ResponseInterface $response,
        StreamInterface $stream
    ) {
        $httpClient->get('/Montpellier-PHP-Meetup/events?status=upcoming')->shouldBeCalled()->willReturn($response);

        $response->getHeaderLine('Content-Type')->shouldBeCalled()->willReturn('application/json');
        $response->getBody()->shouldBeCalled()->willReturn($stream);
        $stream->__toString()->shouldBeCalled()->willReturn(file_get_contents(__DIR__ . '/../../fixtures/get_events.json'));

        $results = $this->ofGroup('Montpellier-PHP-Meetup');
        $results->shouldBeArray();
        $results->shouldHaveCount(1);
        $event = $results[0];
        $event->shouldBeAnInstanceOf(Event::class);
        $event->id->shouldBe('235957132');
        $event->name->shouldBe('Apéro PHP Décembre 2016 + repas');
    }
}
