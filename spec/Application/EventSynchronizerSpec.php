<?php

namespace spec\Application;

use Application\DTO\EventDTOCollection;
use Application\EventFactory;
use Application\EventProvider;
use Application\EventSynchronizer;
use Domain\Model\City\Cities;
use Domain\Model\City\City;
use Domain\Model\City\CityConfiguration;
use Domain\Model\City\CityConfigurationRepository;
use Domain\Model\Event\Event;
use Domain\Model\Event\EventId;
use Domain\Model\Event\EventRepository;
use Domain\Model\Event\Events;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

class EventSynchronizerSpec extends ObjectBehavior
{
    /** @var City */
    private $city;

    function let(
        EventProvider $provider,
        EventRepository $eventRepository,
        LoggerInterface $logger
    ) {
        $this->city = City::named('Montpellier', 43.6, 3.8833);

        $this->beConstructedWith(
            new Cities($this->city),
            $provider,
            $eventRepository,
            $logger
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EventSynchronizer::class);
    }

    function it_should_synchronize_upcoming_events(
        EventProvider $provider,
        EventRepository $eventRepository
    ) {
        $event = EventUtil::generateEvent($this->city);
        $provider->getUpcomingEvents($this->city)->shouldBeCalled()->willReturn(new Events($event));

        $eventRepository
            ->contains(EventId::fromString('123'))
            ->shouldBeCalled()
            ->willReturn(false);

        $eventRepository->add($event)->shouldBeCalled();

        $this->synchronize();
    }

    function it_should_update_synchronized_events(
        EventProvider $provider,
        EventRepository $eventRepository
    ) {
        $event = EventUtil::generateEvent($this->city);
        $provider->getUpcomingEvents($this->city)->shouldBeCalled()->willReturn(new Events($event));

        $eventRepository
            ->contains(EventId::fromString('123'))
            ->shouldBeCalled()
            ->willReturn(true);

        $eventRepository->update($event)->shouldBeCalled();
        $eventRepository->add($event)->shouldNotBeCalled();

        $this->synchronize();
    }
}
