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
        $count = (int) $this->connection
            ->fetchColumn(
                'SELECT COUNT(event_id) FROM events WHERE event_id = ?',
                [(string) $eventId]
            );

        return $count > 0;
    }

    private function convertEventToArray(Event $event) : array
    {
        $data = [
            'event_id' => (string) $event->getId(),
            'city' => $event->getCity()->getId(),
            'name' => $event->getName(),
            'description' => $event->getDescription(),
            'link' => $event->getLink(),
            'duration' => $event->getDuration(),
            'created_at' => $event->getCreatedAt()->format('Y-m-d H:i:s.uP'),
            'planned_at' => $event->getPlannedAt()->format('Y-m-d H:i:s.uP'),
            'number_of_members' => $event->getNumberOfMembers(),
            'limit_of_members' => $event->getLimitOfMembers(),
            'group_name' => $event->getGroup() ? $event->getGroup()->getName() : null,
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
