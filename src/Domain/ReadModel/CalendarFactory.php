<?php

declare(strict_types = 1);

namespace Domain\ReadModel;

final class CalendarFactory
{
    public static function create(Event ...$events) : Calendar
    {
        $eventsGroupedByMonths = array_reduce(
            $events,
            function (array $events, Event $event) {
                $date = $event->plannedAt;
                $key = sprintf('%d-%d', $date->format('Y'), $date->format('m'));
                $events[$key][] = $event;

                return $events;
            },
            []
        );

        $monthEvents = array_map(function ($period, iterable $events) {
            [$year, $month] = explode('-', $period);

            return new MonthEvents($month, $year, ...$events);
        }, array_keys($eventsGroupedByMonths), $eventsGroupedByMonths);

        return new Calendar(...$monthEvents);
    }
}
