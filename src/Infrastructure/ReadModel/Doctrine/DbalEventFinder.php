<?php

declare(strict_types=1);

namespace Infrastructure\ReadModel\Doctrine;

use Doctrine\DBAL\Connection;
use Domain\ReadModel\Event;
use Domain\ReadModel\EventFinder;
use Domain\ReadModel\Events;

final class DbalEventFinder implements EventFinder
{
    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findNextEvents() : Events
    {
        $sql = <<<SQL
SELECT e.*, g.name AS group_name, g.link AS group_link 
FROM events AS e 
INNER JOIN groups AS g ON (e.group_id = g.group_id) 
WHERE e.planned_at > ? 
ORDER BY e.planned_at;
SQL;

        $results = $this->connection
            ->executeQuery($sql, [
                (new \DateTimeImmutable())->format(DATE_ATOM),
            ])
            ->fetchAll();

        return new Events(...array_map(function (array $event) {
            $convertUTCToDateTime = function (string $date) {
                $dateTime = new \DateTime($date, new \DateTimeZone('UTC'));
                $dateTime->setTimezone(new \DateTimeZone('Europe/Paris'));

                return $dateTime;
            };

            $event['created_at'] = $convertUTCToDateTime($event['created_at']);
            $event['planned_at'] = $convertUTCToDateTime($event['planned_at']);

            return Event::fromData($event);
        }, $results));
    }
}
