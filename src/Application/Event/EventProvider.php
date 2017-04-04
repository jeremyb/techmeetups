<?php

declare(strict_types=1);

namespace Application\Event;

use Application\Event\DTO\EventDTO;

interface EventProvider
{
    /**
     * @param array $sources
     *
     * @return EventDTO[]
     */
    public function getEvents(array $sources) : array;
}
