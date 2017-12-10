<?php

declare(strict_types=1);

namespace UI\Action;

use Domain\ReadModel\StatsGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

final class Stats implements Action
{
    /** @var StatsGenerator */
    private $statsGenerator;
    /** @var Twig_Environment */
    private $twig;

    public function __construct(
        StatsGenerator $statsGenerator,
        Twig_Environment $twig
    ) {
        $this->statsGenerator = $statsGenerator;
        $this->twig = $twig;
    }

    public function __invoke(Request $request) : Response
    {
        return new Response(
            $this->twig->render('stats.html.twig', [
                'stats' => $this->statsGenerator->generate()->toArray(),
            ])
        );
    }
}
