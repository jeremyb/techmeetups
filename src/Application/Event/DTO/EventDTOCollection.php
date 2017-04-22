<?php

declare(strict_types=1);

namespace Application\Event\DTO;

final class EventDTOCollection implements \IteratorAggregate, \Countable
{
    /** @var EventDTO[] */
    private $events;

    public function __construct(EventDTO ...$events)
    {
        $this->events = $events;
    }

    public function getIterator() : \Traversable
    {
        return new \ArrayIterator($this->events);
    }

    public function count() : int
    {
        return count($this->events);
    }
}
