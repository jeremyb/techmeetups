<?php

declare(strict_types = 1);

namespace Domain\ReadModel;

interface EventFinder
{
    /**
     * @return Event[]
     */
    public function findNextEvents() : iterable;
}
