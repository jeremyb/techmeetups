<?php
declare(strict_types=1);

namespace Infrastructure\Api\Meetup;

use Application\Event\DTO\EventDTO;
use Application\Event\EventProvider as EventProviderInterface;
use Meetup\Meetup;

final class EventProvider implements EventProviderInterface
{
    /** @var Meetup */
    private $meetup;

    public function __construct(Meetup $meetup)
    {
        $this->meetup = $meetup;
    }

    public function getEvents(array $sources) : array
    {
        $events = [];
        foreach ($sources as $group) {
            $eventsDto = $this->meetup->events()->ofGroup($group);

            foreach ($eventsDto as $eventDto) {
                $data = [
                    'provider_id' => $eventDto->id,
                    'name' => $eventDto->name,
                    'description' => $eventDto->description,
                    'link' => $eventDto->link,
                    'duration' => $eventDto->duration,
                    'planned_at' => $eventDto->time,
                ];

                if (null !== $eventDto->venue) {
                    $data = array_merge($data, [
                        'venue_name' => $eventDto->venue->name,
                        'venue_lat' => $eventDto->venue->lat,
                        'venue_lon' => $eventDto->venue->lon,
                        'venue_address' => $eventDto->venue->address1,
                        'venue_city' => $eventDto->venue->city,
                        'venue_country' => $eventDto->venue->localizedCountryName,
                    ]);
                }

                if (null !== $eventDto->group) {
                    $data['group_name'] = $eventDto->group->name;
                }

                $events[] = EventDTO::fromData($data);
            }
        }

        return $events;
    }
}
