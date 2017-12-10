<?php

declare(strict_types=1);

namespace spec\Application;

use Domain\Model\City\City;
use Domain\Model\Event\Event;
use Domain\Model\Event\EventId;
use Domain\Model\Event\Group;
use Domain\Model\Event\GroupId;
use Domain\Model\Event\Venue;

final class EventUtil
{
    public static function generateEvent(City $city) : Event
    {
        return Event::create(
            EventId::fromString('123'),
            $city,
            'First event',
            'lorem ipsum',
            'https://www.meetup.com/',
            120,
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
            50,
            60,
            (function () {
                return new Venue('Somewhere', null, null, null, 'Montpellier', null);
            })(),
            (function () {
                return new Group(
                    GroupId::fromString('321'),
                    'Group',
                    'group',
                    '',
                    '',
                    new \DateTimeImmutable('-2 years')
                );
            })()
        );
    }
}
