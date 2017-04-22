<?php

declare(strict_types=1);

namespace UI\Action;

use Application\Event\ICalFactory;
use Domain\ReadModel\EventFinder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ICal implements Action
{
    /** @var EventFinder */
    private $eventFinder;

    public function __construct(EventFinder $eventFinder)
    {
        $this->eventFinder = $eventFinder;
    }

    public function __invoke(Request $request) : Response
    {
        $calendar = ICalFactory::create($this->eventFinder->findNextEvents());

        return new Response($calendar->serialize(), Response::HTTP_OK, [
            'Content-type' => 'text/calendar; charset=utf-8',
        ]);
    }
}
