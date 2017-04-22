<?php

declare(strict_types=1);

namespace Infrastructure\Api\Meetup;

use Application\Event\DTO\EventDTO;
use Application\Event\DTO\EventDTOCollection;
use Application\Event\EventProvider as EventProviderInterface;
use Domain\Model\City\CityConfiguration;
use Meetup\Meetup;

final class EventProvider implements EventProviderInterface
{
    /** @var Meetup */
    private $meetup;

    public function __construct(Meetup $meetup)
    {
        $this->meetup = $meetup;
    }

    public function getEvents(CityConfiguration $cityConfiguration) : EventDTOCollection
    {
        $events = [];
        foreach ($cityConfiguration->getMeetups() as $group) {
            $meetupEvents = $this->meetup->events()->ofGroup($group);

            foreach ($meetupEvents as $meetupEvent) {
                $data = [
                    'provider_id' => $meetupEvent->id,
                    'name' => $meetupEvent->name,
                    'description' => $meetupEvent->description,
                    'link' => $meetupEvent->link,
                    'duration' => $meetupEvent->duration,
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
