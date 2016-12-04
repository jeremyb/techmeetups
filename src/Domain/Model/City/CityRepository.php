<?php
declare(strict_types=1);

namespace Domain\Model\City;

interface CityRepository
{
    /**
     * @param City $city
     *
     * @return void
     */
    public function add(City $city);

    /**
     * @return City[]
     */
    public function findAll(): array;

    public function ofName($name): City;
}