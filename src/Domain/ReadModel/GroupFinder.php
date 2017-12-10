<?php

declare(strict_types=1);

namespace Domain\ReadModel;

interface GroupFinder
{
    public function findAll() : Groups;
}
