<?php
declare(strict_types=1);

namespace Application\Event;

interface EventProvider
{
    public function getEvents(array $sources): array;
}
