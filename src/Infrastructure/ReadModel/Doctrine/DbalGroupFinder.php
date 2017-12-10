<?php

declare(strict_types=1);

namespace Infrastructure\ReadModel\Doctrine;

use Doctrine\DBAL\Connection;
use Domain\ReadModel\Group;
use Domain\ReadModel\GroupFinder;
use Domain\ReadModel\Groups;

final class DbalGroupFinder implements GroupFinder
{
    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findAll() : Groups
    {
        $sql = <<<SQL
SELECT 
  g.*, 
  (
    SELECT COUNT(event_id) 
    FROM events AS e 
    WHERE e.group_id = g.group_id
  ) AS number_of_events, 
  (
    SELECT MAX(planned_at) 
    FROM events AS e 
    WHERE e.group_id = g.group_id 
    AND planned_at <= CURRENT_TIMESTAMP
  ) AS last_event, 
  (
    SELECT planned_at 
    FROM events AS e 
    WHERE e.group_id = g.group_id 
    AND planned_at >= CURRENT_TIMESTAMP 
    ORDER BY planned_at 
    LIMIT 1
  ) AS next_event 
FROM groups AS g 
ORDER BY g.name;
SQL;

        $results = $this->connection->executeQuery($sql)->fetchAll();

        return new Groups(...array_map(function (array $group) {
            $convertUTCToDateTime = function (string $date) {
                $dateTime = new \DateTimeImmutable($date, new \DateTimeZone('UTC'));
                $dateTime->setTimezone(new \DateTimeZone('Europe/Paris'));

                return $dateTime;
            };

            $group['created_at'] = $convertUTCToDateTime($group['created_at']);
            $group['last_event'] = !empty($group['last_event']) ? $convertUTCToDateTime($group['last_event']) : null;
            $group['next_event'] = !empty($group['next_event']) ? $convertUTCToDateTime($group['next_event']) : null;

            return Group::fromData($group);
        }, $results));
    }
}
