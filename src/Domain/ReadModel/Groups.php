<?php

declare(strict_types=1);

namespace Domain\ReadModel;

final class Groups implements \IteratorAggregate, \Countable
{
    /** @var Group[] */
    private $groups;

    public function __construct(Group ...$groups)
    {
        $this->groups = $groups;
    }

    public function getIterator() : \Traversable
    {
        return new \ArrayIterator($this->groups);
    }

    public function count() : int
    {
        return \count($this->groups);
    }
}
