<?php

declare(strict_types=1);

namespace spec\Application;

use Application\DTO\EventDTO;

final class EventUtil
{
    public static function generateEvent() : EventDTO
    {
        return EventDTO::fromData([
            'provider_id' => '123',
            'name' => 'First event',
            'description' => 'lorem ipsum',
            'link' => 'https://www.meetup.com/',
            'duration' => 120,
            'created_at' => new \DateTimeImmutable(),
            'planned_at' => new \DateTimeImmutable(),
            'number_of_members' => 50,
            'limit_of_members' => 60,
            'venue_city' => 'Montpellier',
            'group_name' => 'AFUP Montpellier',
        ]);
    }
}
