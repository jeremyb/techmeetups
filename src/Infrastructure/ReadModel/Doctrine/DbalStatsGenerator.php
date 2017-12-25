<?php

declare(strict_types=1);

namespace Infrastructure\ReadModel\Doctrine;

use Doctrine\DBAL\Connection;
use Domain\ReadModel\Stats;
use Domain\ReadModel\StatsGenerator;

final class DbalStatsGenerator implements StatsGenerator
{
    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function generate() : Stats
    {
        $convertDateToQuery = function (string $date) {
            return (new \DateTime($date))
                ->setTimezone(new \DateTimeZone('UTC'))
                ->format('Y-m-d H:i:s.uP');
        };

        $eventStats = $this->connection->createQueryBuilder()
            ->addSelect('COUNT(*) AS number_of_events')
            ->addSelect('AVG(number_of_members) AS average_registrations')
            ->addSelect('MIN(planned_at) AS first_event')
            ->addSelect('MAX(planned_at) AS last_event')
            ->from('events')
            ->where('planned_at < now()')
            ->execute()
            ->fetch();

        $numberOfEventsPerYear = $this->connection->createQueryBuilder()
            ->addSelect('EXTRACT(year from planned_at) AS year')
            ->addSelect('COUNT(*) AS total_events')
            ->from('events')
            ->where('planned_at >= ? AND planned_at <= ?')
            ->setParameters([
                $convertDateToQuery('first day of -5 years 00:00'),
                $convertDateToQuery('last day of this year 00:00'),
            ])
            ->groupBy('year')
            ->orderBy('year')
            ->execute()
            ->fetchAll();

        $numberOfEventsPerMonth = $this->connection->createQueryBuilder()
            ->addSelect('EXTRACT(year from planned_at) AS year')
            ->addSelect('EXTRACT(month from planned_at) AS month')
            ->addSelect('COUNT(*) AS total_events')
            ->from('events')
            ->where('planned_at > ? AND planned_at < ?')
            ->setParameters([
                $convertDateToQuery('last day of last year 00:00'),
                $convertDateToQuery('first day of next month 00:00'),
            ])
            ->groupBy('year, month')
            ->orderBy('year, month')
            ->execute()
            ->fetchAll();

        $numberOfEventsPerMonth = array_map(function (array $data) {
            return [
                'date' => new \DateTimeImmutable(
                    sprintf('%d-%02s-01', $data['year'], $data['month']),
                    new \DateTimeZone('Europe/Paris')
                ),
                'total_events' => $data['total_events'],
            ];
        }, $numberOfEventsPerMonth);

        $popularEvents = $this->connection->createQueryBuilder()
            ->select('number_of_members, name, link')
            ->from('events')
            ->orderBy('number_of_members', 'DESC')
            ->setMaxResults(10)
            ->execute()
            ->fetchAll();

        $popularGroups = $this->connection->createQueryBuilder()
            ->select('g.name, COUNT(e.event_id) AS total_events')
            ->from('groups', 'g')
            ->innerJoin('g', 'events', 'e', 'g.group_id = e.group_id')
            ->groupBy('g.name')
            ->orderBy('total_events', 'DESC')
            ->setMaxResults(10)
            ->execute()
            ->fetchAll();

        return Stats::create(
            array_merge(
                $eventStats,
                [
                    'number_of_events_per_year' => $numberOfEventsPerYear,
                    'number_of_events_per_month' => $numberOfEventsPerMonth,
                    'popular_events' => $popularEvents,
                    'popular_groups' => $popularGroups,
                ]
            )
        );
    }
}
