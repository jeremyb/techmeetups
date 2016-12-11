<?php
declare(strict_types=1);

namespace Infrastructure\Persistence\InMemory;

use Domain\Model\City\City;
use Domain\Model\City\CityNotFound;
use Domain\Model\City\CityRepository;

final class InMemoryCityRepository implements CityRepository
{
    /** @var City[] */
    private $cities;

    public function __construct(array $cities = [])
    {
        $this->cities = $cities;
    }

    public function add(City $city)
    {
        $this->cities[$city->getId()] = $city;
    }

    public function findAll(): array
    {
        return $this->cities;
    }

    public function ofName($name): City
    {
        foreach ($this->cities as $city) {
            if ($name === $city->getName()) {
                return $city;
            }
        }

        throw CityNotFound::named($name);
    }
}
