<?php

declare(strict_types=1);

namespace spec\Domain\ReadModel;

use Domain\ReadModel\Calendar;
use Domain\ReadModel\Event;
use PhpSpec\ObjectBehavior;

class CalendarFactorySpec extends ObjectBehavior
{
    function it_should_generate_a_calendar()
    {
        $calendar = self::create(...$this->generateEvents());
        $calendar->shouldBeAnInstanceOf(Calendar::class);
        $calendar->shouldHaveCount(4);
        $calendar->getIterator()[0]->shouldHaveCount(1);
    }

    private function generateEvents() : array
    {
        return array_map(function ($index) {
            return Event::fromData([
                'event_id' => $index,
                'name' => sprintf('Event #%d', $index),
                'description' => 'lorem ipsum',
                'created_at' => new \DateTimeImmutable(),
                'planned_at' => new \DateTimeImmutable(sprintf('+%d month', $index)),
                'venue_name' => 'Meetup',
                'group_name' => 'Group',
            ]);
        }, range(0, 3));
    }
}
