<?php

declare(strict_types=1);

namespace spec\Application;

use Application\EventImporter;
use Application\EventProvider;
use Domain\Model\City\Cities;
use Domain\Model\City\City;
use Domain\Model\Event\EventId;
use Domain\Model\Event\EventRepository;
use Domain\Model\Event\Events;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;

class EventImporterSpec extends ObjectBehavior
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
        $this->shouldHaveType(EventImporter::class);
    }

    function it_should_import_past_events(
        EventProvider $provider,
        EventRepository $eventRepository
    ) {
        $event = EventUtil::generateEvent($this->city);
        $provider->importPastEvents($this->city)->shouldBeCalled()->willReturn(new Events($event));

        $eventRepository
            ->contains(EventId::fromString('123'))
            ->shouldBeCalled()
            ->willReturn(false);

        $eventRepository->add($event)->shouldBeCalled();

        $this->import();
    }
}
