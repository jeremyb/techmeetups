<?php

declare(strict_types=1);

namespace UI\Action;

use Domain\ReadModel\GroupFinder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

final class Groups implements Action
{
    /** @var GroupFinder */
    private $groupFinder;
    /** @var EngineInterface */
    private $templating;

    public function __construct(
        GroupFinder $groupFinder,
        EngineInterface $templating
    ) {
        $this->groupFinder = $groupFinder;
        $this->templating = $templating;
    }

    public function __invoke(Request $request) : Response
    {
        return new Response(
            $this->templating->render('groups.html.twig', [
                'groups' => $this->groupFinder->findAll(),
            ])
        );
    }
}
