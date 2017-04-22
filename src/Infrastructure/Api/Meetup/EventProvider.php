<?php

declare(strict_types=1);

namespace Infrastructure\Api\Meetup;

use Application\DTO\EventDTO;
use Application\DTO\EventDTOCollection;
use Application\EventProvider as EventProviderInterface;
use Domain\Model\City\CityConfiguration;
use Meetup\Meetup;
use Meetup\Resource\Events;

final class EventProvider implements EventProviderInterface
{
    /** @var Meetup */
    private $meetup;

    public function __construct(Meetup $meetup)
    {
        $this->meetup = $meetup;
    }

    public function importPastEvents(CityConfiguration $cityConfiguration): EventDTOCollection
    {
        return $this->getEvents($cityConfiguration, Events::PAST);
    }

    public function getUpcomingEvents(CityConfiguration $cityConfiguration) : EventDTOCollection
    {
        return $this->getEvents($cityConfiguration, Events::UPCOMING);
    }

    private function getEvents(
        CityConfiguration $cityConfiguration,
        string $status
    ) : EventDTOCollection {
        $events = [];
        foreach ($cityConfiguration->getMeetups() as $group) {
            $meetupEvents = $this->meetup->events()->ofGroup($group, $status);

            foreach ($meetupEvents as $meetupEvent) {
                $data = [
                    'provider_id' => $meetupEvent->id,
                    'name' => $meetupEvent->name,
                    'description' => $meetupEvent->description,
                    'link' => $meetupEvent->link,
                    'duration' => $meetupEvent->duration,
                    'created_at' => $meetupEvent->created,
                    'planned_at' => $meetupEvent->time,
                ];

                if (null !== $meetupEvent->venue) {
                    $data = array_merge($data, [
                        'venue_name' => $meetupEvent->venue->name,
                        'venue_lat' => $meetupEvent->venue->lat,
                        'venue_lon' => $meetupEvent->venue->lon,
                        'venue_address' => $meetupEvent->venue->address1,
                        'venue_city' => $meetupEvent->venue->city,
                        'venue_country' => $meetupEvent->venue->localizedCountryName,
                    ]);
                }

                if (null !== $meetupEvent->group) {
                    $data['group_name'] = $meetupEvent->group->name;
                }

                $events[] = EventDTO::fromData($data);
            }
        }

        return new EventDTOCollection(...$events);
    }
}
