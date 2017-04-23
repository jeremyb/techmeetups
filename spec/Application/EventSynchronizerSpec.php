<?php

namespace spec\Application;

use Application\DTO\EventDTOCollection;
use Application\EventFactory;
use Application\EventProvider;
use Application\EventSynchronizer;
use Domain\Model\City\City;
use Domain\Model\City\CityConfiguration;
use Domain\Model\City\CityConfigurationRepository;
use Domain\Model\Event\Event;
use Domain\Model\Event\EventId;
use Domain\Model\Event\EventRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

class EventSynchronizerSpec extends ObjectBehavior
{
    function let(
        CityConfigurationRepository $cityConfigurationRepository,
        EventProvider $provider,
        EventRepository $eventRepository,
        LoggerInterface $logger
    ) {
        $this->beConstructedWith(
            $cityConfigurationRepository,
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
        CityConfigurationRepository $cityConfigurationRepository,
        EventProvider $provider,
        EventRepository $eventRepository
    ) {
        $cityConfigurationRepository->findAll()->shouldBeCalled()->willReturn([
            $cityConfiguration = new CityConfiguration(
                $city = City::named('Montpellier'),
                'Montpellier-PHP-Meetup'
            ),
        ]);

        $provider->getUpcomingEvents($cityConfiguration)->shouldBeCalled()->willReturn(
            new EventDTOCollection(
                $eventDto = EventUtil::generateEvent()
            )
        );

        $eventRepository
            ->contains(EventId::fromString('123'))
            ->shouldBeCalled()
            ->willReturn(false);

        $event = EventFactory::create($eventDto, $city);
        $eventRepository->add($event)->shouldBeCalled();

        $this->synchronize();
    }

    function it_should_update_synchronized_events(
        CityConfigurationRepository $cityConfigurationRepository,
        EventProvider $provider,
        EventRepository $eventRepository
    ) {
        $cityConfigurationRepository->findAll()->shouldBeCalled()->willReturn([
            $cityConfiguration = new CityConfiguration(
                $city = City::named('Montpellier'),
                'Montpellier-PHP-Meetup'
            ),
        ]);

        $provider->getUpcomingEvents($cityConfiguration)->shouldBeCalled()->willReturn(
            new EventDTOCollection(
                $eventDto = EventUtil::generateEvent()
            )
        );

        $eventRepository
            ->contains(EventId::fromString('123'))
            ->shouldBeCalled()
            ->willReturn(true);

        $event = EventFactory::create($eventDto, $city);
        $eventRepository->update($event)->shouldBeCalled();
        $eventRepository->add($event)->shouldNotBeCalled();

        $this->synchronize();
    }
}
