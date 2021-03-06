<?php

declare(strict_types=1);

namespace UI\Action;

use Domain\ReadModel\StatsGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

final class Stats implements Action
{
    /** @var StatsGenerator */
    private $statsGenerator;
    /** @var EngineInterface */
    private $templating;

    public function __construct(
        StatsGenerator $statsGenerator,
        EngineInterface $templating
    ) {
        $this->statsGenerator = $statsGenerator;
        $this->templating = $templating;
    }

    public function __invoke(Request $request) : Response
    {
        return new Response(
            $this->templating->render('stats.html.twig', [
                'stats' => $this->statsGenerator->generate()->toArray(),
            ])
        );
    }
}
