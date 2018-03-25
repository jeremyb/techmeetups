<?php

declare(strict_types=1);

namespace Domain\ReadModel;

interface EventFinder
{
    public function findNextEvents() : Events;
    public function findNextEventsOfGroups(array $groupIds) : Events;
}
