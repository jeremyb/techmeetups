<?php

namespace spec\Application;

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

    function it_should_import_past_events(
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

        $provider->importPastEvents($cityConfiguration)->shouldBeCalled()->willReturn(
            new EventDTOCollection(EventUtil::generateEvent())
        );

        $eventRepository
            ->contains(EventId::fromString('123'))
            ->shouldBeCalled()
            ->willReturn(false);

        $eventRepository->add(Argument::type(Event::class))->shouldBeCalled();

        $this->import();
    }
}
