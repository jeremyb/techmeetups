<?php

declare(strict_types=1);

namespace Domain\Model\Event;

interface EventRepository
{
    public function add(Event $event) : void;
    public function update(Event $event) : void;
    public function contains(EventId $eventId) : bool;
}
