<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;
use Domain\Model\Event\Event;
use Domain\Model\Event\EventId;
use Domain\Model\Event\EventRepository;

final class DbalEventRepository implements EventRepository
{
    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(Event $event) : void
    {
        $this->connection->insert('events', $this->convertEventToArray($event));
    }

    public function update(Event $event) : void
    {
        $this->connection->update(
            'events',
            $this->convertEventToArray($event),
            ['event_id' => (string) $event->getId()]
        );
    }

    public function contains(EventId $eventId) : bool
    {
        return (bool) $this->connection
            ->fetchColumn(
                'SELECT EXISTS(SELECT 1 FROM events WHERE event_id = ?)',
                [(string) $eventId]
            );
    }

    private function convertEventToArray(Event $event) : array
    {
        $convertDateTimeToUTC = function (\DateTimeImmutable $date) {
            return $date
                ->setTimezone(new \DateTimeZone('UTC'))
                ->format('Y-m-d H:i:s.uP');
        };

        $data = [
            'event_id' => (string) $event->getId(),
            'group_id' => (string) $event->getGroup()->getId(),
            'city' => $event->getCity()->getId(),
            'name' => $event->getName(),
            'description' => $event->getDescription(),
            'link' => $event->getLink(),
            'duration' => $event->getDuration(),
            'created_at' => $convertDateTimeToUTC($event->getCreatedAt()),
            'planned_at' => $convertDateTimeToUTC($event->getPlannedAt()),
            'number_of_members' => $event->getNumberOfMembers(),
            'limit_of_members' => $event->getLimitOfMembers(),
        ];

        if (null !== $venue = $event->getVenue()) {
            $data['venue_name'] = $venue->getName();
            $data['venue_address'] = $venue->getAddress();
            $data['venue_city'] = $venue->getCity();
            $data['venue_country'] = $venue->getCountry();
            $data['venue_lat'] = $venue->getLat();
            $data['venue_lon'] = $venue->getLon();
        }

        return $data;
    }
}
