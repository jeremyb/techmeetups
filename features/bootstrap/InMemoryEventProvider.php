<?php

declare(strict_types=1);

namespace Behat\Features;

use Application\EventProvider;
use Domain\Model\City\City;
use Domain\Model\Event\Event;
use Domain\Model\Event\EventId;
use Domain\Model\Event\Events;
use Domain\Model\Event\Group;
use Domain\Model\Event\Venue;

final class InMemoryEventProvider implements EventProvider
{
    public function importPastEvents(City $city) : Events
    {
        return new Events();
    }

    public function getUpcomingEvents(City $city) : Events
    {
        return new Events(
            Event::create(
                EventId::fromString('235957132'),
                $city,
                'First event',
                'lorem ipsum',
                'https://www.meetup.com/',
                120,
                new \DateTimeImmutable('yesterday'),
                new \DateTimeImmutable('+2 days'),
                50,
                60,
                (function () {
                    return new Venue('Somewhere', null, null, null, 'Montpellier', null);
                })(),
                (function () {
                    return new Group('Group', 'group', '', '', new \DateTimeImmutable('-2 years'));
                })()
            )
        );
    }
}
