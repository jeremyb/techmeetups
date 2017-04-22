<?php

declare(strict_types=1);

namespace UI\Action;

use Application\FeedFactory;
use Domain\ReadModel\EventFinder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Feed implements Action
{
    /** @var EventFinder */
    private $eventFinder;

    public function __construct(EventFinder $eventFinder)
    {
        $this->eventFinder = $eventFinder;
    }

    public function __invoke(Request $request) : Response
    {
        $format = $request->getRequestFormat();

        $feed = FeedFactory::create($this->eventFinder->findNextEvents());

        return new Response($feed->export($format), Response::HTTP_OK, [
            'Content-type' => sprintf('application/%s+xml;charset=UTF-8', $format),
        ]);
    }
}
