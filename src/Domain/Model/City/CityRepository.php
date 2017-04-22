<?php

declare(strict_types=1);

namespace Domain\Model\City;

interface CityRepository
{
    public function add(City $city) : void;

    /**
     * @return City[]
     */
    public function findAll() : array;

    public function ofName($name) : City;
}
