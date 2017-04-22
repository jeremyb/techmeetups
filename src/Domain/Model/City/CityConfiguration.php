<?php

declare(strict_types=1);

namespace Domain\Model\City;

final class CityConfiguration
{
    /** @var City */
    private $city;
    /** @var string[] */
    private $meetups;

    public function __construct(City $city, string ...$meetups)
    {
        $this->city = $city;
        $this->meetups = $meetups;
    }

    public function getCity() : City
    {
        return $this->city;
    }

    public function getMeetups() : array
    {
        return $this->meetups;
    }
}
