<?php

declare(strict_types=1);

namespace Domain\Model\City;

interface CityConfigurationRepository
{
    /**
     * @return CityConfiguration[]
     */
    public function findAll() : array;
}
