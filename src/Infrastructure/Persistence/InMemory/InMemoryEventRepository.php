<?php
declare(strict_types=1);

namespace Infrastructure\Persistence\InMemory;

use Domain\Model\Event\Event;
use Domain\Model\Event\EventRepository;

final class InMemoryEventRepository implements EventRepository
{
    /** @var Event[] */
    private $events;

    public function __construct()
    {
        $this->events = [];
    }

    public function add(Event $event)
    {
        $this->events[$event->getId()] = $event;
    }

    public function findAll()
    {
        return $this->events;
    }
}
