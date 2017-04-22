<?php

namespace spec\Application;

use Application\DTO\EventDTO;
use Application\DTO\EventDTOCollection;
use Application\EventImporter;
use Application\EventProvider;
use Domain\Model\City\City;
use Domain\Model\City\CityConfiguration;
use Domain\Model\City\CityConfigurationRepository;
use Domain\Model\Event\Event;
use Domain\Model\Event\EventId;
use Domain\Model\Event\EventRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

class EventImporterSpec extends ObjectBehavior
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
        $this->shouldHaveType(EventImporter::class);
    }

    function it_should_import_upcoming_events(
        CityConfigurationRepository $cityConfigurationRepository,
        EventProvider $provider,
        EventRepository $eventRepository
    ) {
        $cityConfigurationRepository->findAll()->shouldBeCalled()->willReturn([
            $cityConfiguration = new CityConfiguration(
                City::named('Montpellier'),
                'Montpellier-PHP-Meetup'
            ),
        ]);

        $provider->getUpcomingEvents($cityConfiguration)->shouldBeCalled()->willReturn(
            new EventDTOCollection(
                EventDTO::fromData([
                    'provider_id' => '123',
                    'name' => 'First event',
                    'description' => 'lorem ipsum',
                    'link' => 'https://www.meetup.com/',
                    'duration' => 120,
                    'created_at' => new \DateTimeImmutable(),
                    'planned_at' => new \DateTimeImmutable(),
                    'venue_city' => 'Montpellier',
                    'group_name' => 'AFUP Montpellier',
                ])
            )
        );

        $eventRepository
            ->contains(EventId::fromString('123'))
            ->shouldBeCalled()
            ->willReturn(false);

        $eventRepository->add(Argument::type(Event::class))->shouldBeCalled();

        $this->importUpcoming();
    }
}
