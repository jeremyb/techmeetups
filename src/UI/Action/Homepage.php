<?php

declare(strict_types=1);

namespace UI\Action;

use Domain\ReadModel\CalendarFactory;
use Domain\ReadModel\EventFinder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

final class Homepage implements Action
{
    /** @var EventFinder */
    private $eventFinder;
    /** @var Twig_Environment */
    private $twig;

    public function __construct(EventFinder $eventFinder, Twig_Environment $twig)
    {
        $this->eventFinder = $eventFinder;
        $this->twig = $twig;
    }

    public function __invoke(Request $request) : Response
    {
        $events = $this->eventFinder->findNextEvents();

        return new Response(
            $this->twig->render('homepage.html.twig', [
                'calendar' => CalendarFactory::create(...$events),
            ])
        );
    }
}
