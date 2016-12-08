<?php

namespace Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;
use Domain\Model\Event\Event;
use Domain\Model\Event\EventRepository;

final class DbalEventRepository implements EventRepository
{
    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(Event $event)
    {
        $data = [
            'event_id' => $event->getId(),
            'name' => $event->getName(),
            'description' => $event->getDescription(),
            'link' => $event->getLink(),
            'duration' => $event->getDuration(),
            'planned_at' => $event->getPlannedAt()->format(DATE_ATOM),
            'group_name' => $event->getGroup() ?? $event->getGroup()->getName(),
        ];

        if (null !== $venue = $event->getVenue()) {
            $data['venue_name'] = $venue->getName();
            $data['venue_address'] = $venue->getAddress();
            $data['venue_city'] = $venue->getCity();
            $data['venue_country'] = $venue->getCountry();
            $data['venue_lat'] = $venue->getLat();
            $data['venue_lon'] = $venue->getLon();
        }

        $this->connection->insert('events', $data);
    }
}
