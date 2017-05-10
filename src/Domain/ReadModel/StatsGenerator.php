<?php

declare(strict_types=1);

namespace Domain\ReadModel;

interface StatsGenerator
{
    public function generate() : Stats;
}
