<?php

declare(strict_types=1);

namespace Infrastructure\Api\Meetup;

use Domain\Model\City\City;
use Domain\Model\Event\Event;
use Domain\Model\Event\EventId;
use Domain\Model\Event\Group;
use Domain\Model\Event\GroupId;
use Domain\Model\Event\Venue;
use Meetup\DTO\Event as EventDTO;
use Meetup\DTO\Group as GroupDTO;
use Meetup\DTO\Venue as VenueDTO;

final class EventFactory
{
    public static function create(City $city, EventDTO $eventDTO) : Event
    {
        return Event::create(
            EventId::fromString($eventDTO->id),
            $city,
            $eventDTO->name,
            $eventDTO->description,
            $eventDTO->link,
            $eventDTO->duration,
            $eventDTO->created,
            $eventDTO->time,
            $eventDTO->numberOfMembers,
            $eventDTO->limitOfMembers,
            (function (VenueDTO $venueDTO = null) {
                if (null === $venueDTO) {
                    return null;
                }

                return new Venue(
                    $venueDTO->name,
                    $venueDTO->lat,
                    $venueDTO->lon,
                    $venueDTO->address1,
                    $venueDTO->city,
                    $venueDTO->localizedCountryName
                );
            })($eventDTO->venue),
            (function (GroupDTO $groupDTO) {
                return new Group(
                    GroupId::fromString((string) $groupDTO->id),
                    $groupDTO->name,
                    sprintf('https://www.meetup.com/%s/', $groupDTO->urlname),
                    $groupDTO->description,
                    null !== $groupDTO->keyPhoto ? $groupDTO->keyPhoto->photoLink : null,
                    $groupDTO->created
                );
            })($eventDTO->group)
        );
    }

    private function __construct()
    {
    }
}
