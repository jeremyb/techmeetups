<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\InMemory;

use Domain\Model\City\CityConfiguration;
use Domain\Model\City\CityConfigurationRepository;

final class InMemoryCityConfigurationRepository implements CityConfigurationRepository
{
    /** @var CityConfiguration[] */
    private $citiesConfiguration;

    public function __construct(CityConfiguration ...$citiesConfiguration)
    {
        $this->citiesConfiguration = $citiesConfiguration;
    }

    public function findAll() : array
    {
        return $this->citiesConfiguration;
    }
}
