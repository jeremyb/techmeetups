<?php

declare(strict_types=1);

namespace Domain\ReadModel;

final class Events implements \IteratorAggregate, \Countable
{
    /** @var Event[] */
    private $events;

    public function __construct(Event ...$events)
    {
        $this->events = $events;
    }

    public function getIterator() : \Traversable
    {
        return new \ArrayIterator($this->events);
    }

    public function count() : int
    {
        return \count($this->events);
    }
}
