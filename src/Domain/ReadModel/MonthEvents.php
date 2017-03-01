<?php

declare(strict_types = 1);

namespace Domain\ReadModel;

final class MonthEvents implements \IteratorAggregate
{
    /** @var string */
    private $month;
    /** @var string */
    private $year;
    /** @var Event[] */
    private $events;

    public function __construct(string $month, string $year, Event ...$events)
    {
        $this->month = $month;
        $this->year = $year;
        $this->events = $events;
    }

    public function getMonth() : string
    {
        return $this->month;
    }

    public function getYear() : string
    {
        return $this->year;
    }

    public function getIterator() : \Traversable
    {
        return new \ArrayIterator($this->events);
    }
}
