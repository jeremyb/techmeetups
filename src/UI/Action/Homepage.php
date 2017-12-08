<?php

declare(strict_types=1);

namespace UI\Action;

use Domain\ReadModel\CalendarFactory;
use Domain\ReadModel\EventFinder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

final class Homepage implements Action
{
    /** @var EventFinder */
    private $eventFinder;
    /** @var EngineInterface */
    private $templating;

    public function __construct(EventFinder $eventFinder, EngineInterface $templating)
    {
        $this->eventFinder = $eventFinder;
        $this->templating = $templating;
    }

    public function __invoke(Request $request) : Response
    {
        $events = $this->eventFinder->findNextEvents();

        return new Response(
            $this->templating->render('homepage.html.twig', [
                'calendar' => CalendarFactory::create(...$events),
            ])
        );
    }
}
