<?php

declare(strict_types=1);

namespace Domain\ReadModel;

final class Calendar implements \IteratorAggregate
{
    /** @var MonthEvents[] */
    private $events;

    public function __construct(MonthEvents ...$events)
    {
        $this->events = $events;
    }

    public function getIterator() : \Traversable
    {
        return new \ArrayIterator($this->events);
    }
}
