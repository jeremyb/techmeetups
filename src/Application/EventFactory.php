<?php

declare(strict_types=1);

namespace Application;

use Application\DTO\EventDTO;
use Domain\Model\City\City;
use Domain\Model\Event\Event;
use Domain\Model\Event\EventId;
use Domain\Model\Event\Group;
use Domain\Model\Event\Venue;

final class EventFactory
{
    public static function create(EventDTO $eventDto, City $city) : Event
    {
        if (null !== $eventDto->venueAddress) {
            $venue = new Venue(
                $eventDto->venueName,
                $eventDto->venueLat,
                $eventDto->venueLon,
                $eventDto->venueAddress,
                $eventDto->venueCity,
                $eventDto->venueCountry
            );
        }

        if (null !== $eventDto->groupName) {
            $group = new Group($eventDto->groupName);
        }

        return Event::create(
            EventId::fromString($eventDto->providerId),
            $city,
            $eventDto->name,
            $eventDto->description,
            $eventDto->link,
            $eventDto->duration,
            $eventDto->createdAt,
            $eventDto->plannedAt,
            $venue ?? null,
            $group ?? null
        );
    }

    private function __construct()
    {
    }
}
