<?php

declare(strict_types=1);

namespace Infrastructure\ReadModel\InMemory;

use Domain\Model\Event\Event;
use Domain\ReadModel\Event as EventReadModel;
use Domain\ReadModel\EventFinder;
use Domain\ReadModel\Events;
use Infrastructure\Persistence\InMemory\InMemoryEventRepository;

final class InMemoryEventFinder implements EventFinder
{
    /** @var InMemoryEventRepository */
    private $eventRepository;

    public function __construct(InMemoryEventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function findNextEvents() : Events
    {
        return $this->convertResultToDTO($this->eventRepository->findAll());
    }

    public function findNextEventsOfGroups(array $groupIds) : Events
    {
        $events = array_filter(
            $this->eventRepository->findAll(),
            function (Event $event) use ($groupIds) {
                return \in_array($event->getGroup()->getId(), $groupIds, true);
            }
        );

        return $this->convertResultToDTO($events);
    }

    private function convertResultToDTO(array $results) : Events
    {
        return new Events(...array_map(
            function (Event $event) {
                $venue = $event->getVenue();

                return EventReadModel::fromData([
                    'event_id' => (string) $event->getId(),
                    'name' => $event->getName(),
                    'description' => $event->getDescription(),
                    'link' => $event->getLink(),
                    'duration' => $event->getDuration(),
                    'planned_at' => $event->getPlannedAt(),
                    'number_of_members' => $event->getNumberOfMembers(),
                    'limit_of_members' => $event->getLimitOfMembers(),
                    'venue_name' => $venue ? $venue->getName() : null,
                    'venue_lat' => $venue ? $venue->getLat() : null,
                    'venue_lon' => $venue ? $venue->getLon() : null,
                    'venue_address' => $venue ? $venue->getAddress() : null,
                    'venue_city' => $venue ? $venue->getCity() : null,
                    'venue_country' => $venue ? $venue->getCountry() : null,
                    'group_name' => $event->getGroup()->getName(),
                ]);
            },
            $results
        ));
    }
}
