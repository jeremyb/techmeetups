<?php

declare(strict_types=1);

namespace Domain\Model\City;

final class Cities implements \IteratorAggregate, \Countable
{
    /** @var City[] */
    private $cities;

    public function __construct(City ...$cities)
    {
        $this->cities = $cities;
    }

    public function getCities() : array
    {
        return $this->cities;
    }

    public function getIterator() : \Traversable
    {
        return new \ArrayIterator($this->cities);
    }

    public function count() : int
    {
        return \count($this->cities);
    }
}
