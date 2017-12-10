<?php

declare(strict_types=1);

namespace Domain\ReadModel;

final class MonthEvents implements \IteratorAggregate, \Countable
{
    /** @var \DateTimeImmutable */
    private $month;
    /** @var Event[] */
    private $events;

    public function __construct(\DateTimeImmutable $month, Event ...$events)
    {
        $this->month = $month;
        $this->events = $events;
    }

    public function getMonth() : \DateTimeImmutable
    {
        return $this->month;
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
