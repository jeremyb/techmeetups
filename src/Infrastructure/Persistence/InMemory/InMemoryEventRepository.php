<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\InMemory;

use Domain\Model\Event\Event;
use Domain\Model\Event\EventId;
use Domain\Model\Event\EventRepository;

final class InMemoryEventRepository implements EventRepository
{
    /** @var Event[] */
    private $events;

    public function __construct()
    {
        $this->events = [];
    }

    public function add(Event $event) : void
    {
        $this->events[(string) $event->getId()] = $event;
    }

    public function update(Event $event) : void
    {
    }

    public function contains(EventId $eventId) : bool
    {
        return isset($this->events[(string) $eventId]);
    }

    public function findAll() : array
    {
        return $this->events;
    }
}
