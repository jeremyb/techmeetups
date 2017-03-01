<?php

declare(strict_types = 1);

namespace Infrastructure\ReadModel\Doctrine;

use Doctrine\DBAL\Connection;
use Domain\ReadModel\Event;
use Domain\ReadModel\EventFinder;

final class DbalEventFinder implements EventFinder
{
    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findNextEvents() : iterable
    {
        $results = $this->connection
            ->executeQuery(
                'SELECT * FROM events WHERE planned_at > ? ORDER BY planned_at;',
                [
                    (new \DateTimeImmutable())->format(DATE_ATOM)
                ]
            )
            ->fetchAll();

        return array_map(function (array $event) {
            return Event::fromData($event);
        }, $results);
    }
}
