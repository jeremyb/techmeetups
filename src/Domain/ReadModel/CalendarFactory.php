<?php

declare(strict_types=1);

namespace Domain\ReadModel;

final class CalendarFactory
{
    public static function create(Event ...$events) : Calendar
    {
        $eventsGroupedByMonths = array_reduce(
            $events,
            function (array $events, Event $event) {
                $date = $event->plannedAt;
                $key = sprintf('%d-%s', $date->format('Y'), $date->format('F'));
                $events[$key][] = $event;

                return $events;
            },
            []
        );

        $monthEvents = array_map(function ($period, iterable $events) {
            [$year, $month] = explode('-', $period);
            $month = new \DateTimeImmutable(
                sprintf('first day of %s %d', $month, $year)
            );

            return new MonthEvents($month, ...$events);
        }, array_keys($eventsGroupedByMonths), $eventsGroupedByMonths);

        return new Calendar(...$monthEvents);
    }
}
