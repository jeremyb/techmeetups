<?php

declare(strict_types=1);

namespace Application\Event;

use Application\Event\DTO\EventDTOCollection;
use Domain\Model\City\CityConfiguration;

interface EventProvider
{
    public function getEvents(CityConfiguration $cityConfiguration) : EventDTOCollection;
}
