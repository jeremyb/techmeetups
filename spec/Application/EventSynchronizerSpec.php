<?php

declare(strict_types=1);

namespace spec\Application;

use Application\EventProvider;
use Application\EventSynchronizer;
use Domain\Model\City\Cities;
use Domain\Model\City\City;
use Domain\Model\Event\EventId;
use Domain\Model\Event\EventRepository;
use Domain\Model\Event\Events;
use Domain\Model\Event\GroupRepository;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;

class EventSynchronizerSpec extends ObjectBehavior
{
    /** @var City */
    private $city;

    function let(
        EventProvider $provider,
        GroupRepository $groupRepository,
        EventRepository $eventRepository,
        LoggerInterface $logger
    ) {
        $this->city = City::named('Montpellier', 43.6, 3.8833);

        $this->beConstructedWith(
            new Cities($this->city),
            $provider,
            $groupRepository,
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
        GroupRepository $groupRepository,
        EventRepository $eventRepository
    ) {
        $event = EventUtil::generateEvent($this->city);
        $provider->getUpcomingEvents($this->city)->shouldBeCalled()->willReturn(new Events($event));

        $groupRepository->addOrUpdate($event->getGroup())->shouldBeCalled();

        $eventRepository
            ->contains(EventId::fromString('123'))
            ->shouldBeCalled()
            ->willReturn(false);

        $eventRepository->add($event)->shouldBeCalled();

        $this->synchronize();
    }

    function it_should_update_synchronized_events(
        EventProvider $provider,
        GroupRepository $groupRepository,
        EventRepository $eventRepository
    ) {
        $event = EventUtil::generateEvent($this->city);
        $provider->getUpcomingEvents($this->city)->shouldBeCalled()->willReturn(new Events($event));

        $groupRepository->addOrUpdate($event->getGroup())->shouldBeCalled();

        $eventRepository
            ->contains(EventId::fromString('123'))
            ->shouldBeCalled()
            ->willReturn(true);

        $eventRepository->update($event)->shouldBeCalled();
        $eventRepository->add($event)->shouldNotBeCalled();

        $this->synchronize();
    }
}
