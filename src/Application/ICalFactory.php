<?php

declare(strict_types=1);

namespace Application;

use Domain\ReadModel\Event;
use Domain\ReadModel\Events;
use Sabre\VObject\Component\VCalendar;

final class ICalFactory
{
    public static function create(Events $events) : VCalendar
    {
        $calendar = new VCalendar();
        $calendar->PRODID = 'techmeetups.fr';
        $calendar->add('X-WR-CALNAME', 'TechMeetups - Montpellier');
        $calendar->add('X-WR-CALDESC', 'TechMeetups - L\'agenda des meetups du numérique à Montpellier');
        $calendar->add('VTIMEZONE', [
            'TZID' => 'Europe/Paris',
            'X-LIC-LOCATION' => 'Europe/Paris',
        ]);

        /** @var Event $event */
        foreach ($events as $event) {
            $vEvent = [
                'UID' => $event->eventId,
                'DTSTART' => $event->plannedAt,
                'DTEND' => $event->getEndedAt(),
                'CREATED' => $event->createdAt,
                'SUMMARY' => $event->name,
                'DESCRIPTION' => self::generateEventDescription($event),
                'STATUS' => 'CONFIRMED',
                'CLASS' => 'PUBLIC',
                'LOCATION' => $event->fullVenueAddress(),
                'URL' => $event->link,
            ];

            if (null !== $event->venueLat && null !== $event->venueLon) {
                $vEvent['GEO'] = sprintf('%s;%s', $event->venueLat, $event->venueLon);
            }

            $calendar->add('VEVENT', $vEvent);
        }

        return $calendar;
    }

    private static function generateEventDescription(Event $event) : string
    {
        return <<<TEXT
{$event->groupName}
{$event->fullPlannedAt()}
{$event->venueAddress}, {$event->venueCity}

{$event->extractDescription()}

{$event->link}
TEXT;
    }

    private function __construct()
    {
    }
}
