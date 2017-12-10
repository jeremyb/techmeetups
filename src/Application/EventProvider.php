<?php

declare(strict_types=1);

namespace Application;

use Domain\Model\City\City;
use Domain\Model\Event\Events;

interface EventProvider
{
    public function importPastEvents(City $city) : Events;

    public function getUpcomingEvents(City $city) : Events;
}
