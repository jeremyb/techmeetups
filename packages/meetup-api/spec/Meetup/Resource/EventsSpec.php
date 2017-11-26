<?php

namespace spec\Meetup\Resource;

use Http\Client\Common\HttpMethodsClient;
use Meetup\DTO\Event;
use Meetup\DTO\EventVisibility;
use Meetup\DTO\Query\FindUpcomingEventsQuery;
use Meetup\DTO\Query\GroupEventsQuery;
use Meetup\Hydrator\HydratorFactory;
use Meetup\Resource\Events;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class EventsSpec extends ObjectBehavior
{
    function let(HttpMethodsClient $httpClient)
    {
        $this->beConstructedWith($httpClient, HydratorFactory::create());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Events::class);
    }

    function it_should_find_upcoming_events(
        HttpMethodsClient $httpClient,
        ResponseInterface $response,
        StreamInterface $stream
    ) {
        $httpClient->get('/find/upcoming_events?lat=43.6&lon=3.8833&order=time&page=100&radius=smart&topic_category=292')->shouldBeCalled()->willReturn($response);

        $response->getHeaderLine('Content-Type')->shouldBeCalled()->willReturn('application/json');
        $response->getBody()->shouldBeCalled()->willReturn($stream);
        $stream->__toString()->shouldBeCalled()->willReturn(file_get_contents(__DIR__ . '/../../fixtures/find_upcoming_events.json'));

        $results = $this->findUpcomingEvents(FindUpcomingEventsQuery::from([
            'lat' => 43.6,
            'lon' => 3.8833,
            'radius' => 'smart',
            'order' => 'time',
            'topic_category' => 292,
            'page' => 100,
        ]));
        $results->shouldBeArray();
        $results->shouldHaveCount(1);
        $event = $results[0];
        $event->shouldBeAnInstanceOf(Event::class);
        $event->id->shouldBe('242948843');
        $event->name->shouldBe('Meetup dédié aux CMS : Wordpress, Drupal et eZ Publish');
        $event->time->shouldBeAnInstanceOf(\DateTimeImmutable::class);
        $event->group->id->shouldBe(18724486);
        $event->group->name->shouldBe('AFUP Montpellier');
        $event->group->joinMode->shouldBe('open');
        $event->group->urlname->shouldBe('Montpellier-PHP-Meetup');
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

        $results = $this->ofGroup('Montpellier-PHP-Meetup', GroupEventsQuery::from([
            'status' => GroupEventsQuery::UPCOMING,
        ]));
        $results->shouldBeArray();
        $results->shouldHaveCount(1);
        $event = $results[0];
        $event->shouldBeAnInstanceOf(Event::class);
        $event->id->shouldBe('235957132');
        $event->name->shouldBe('Apéro PHP Décembre 2016 + repas');
        $event->time->shouldBeAnInstanceOf(\DateTimeImmutable::class);
        $event->utcOffset->shouldBe(3600);
        $event->link->shouldBeString();
        $event->visibility->shouldBeAnInstanceOf(EventVisibility::class);
        $event->numberOfMembers->shouldBe(13);
        $event->limitOfMembers->shouldBe(0);
        $event->group->id->shouldBe(18724486);
        $event->group->name->shouldBe('AFUP Montpellier');
    }
}
