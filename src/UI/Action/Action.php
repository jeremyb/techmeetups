<?php

declare(strict_types=1);

namespace UI\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface Action
{
    public function __invoke(Request $request) : Response;
}
