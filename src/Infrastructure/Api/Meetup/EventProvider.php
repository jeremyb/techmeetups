<?php
declare(strict_types=1);

namespace Infrastructure\Api\Meetup;

use Application\Event\EventProvider as EventProviderInterface;
use Meetup\Meetup;

final class EventProvider implements EventProviderInterface
{
    /** @var Meetup */
    private $meetup;

    public function __construct(Meetup $meetup)
    {
        $this->meetup = $meetup;
    }

    public function getEvents(array $sources)
    {
        foreach ($sources as $group) {
            $eventsDto = $this->meetup->events()->ofGroup($group);
        }
    }
}
