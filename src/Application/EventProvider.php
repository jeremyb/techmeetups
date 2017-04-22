<?php

declare(strict_types=1);

namespace Application;

use Application\DTO\EventDTOCollection;
use Domain\Model\City\CityConfiguration;

interface EventProvider
{
    public function importPastEvents(CityConfiguration $cityConfiguration) : EventDTOCollection;
    public function getUpcomingEvents(CityConfiguration $cityConfiguration) : EventDTOCollection;
}
