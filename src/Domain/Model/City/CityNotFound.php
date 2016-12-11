<?php

namespace Domain\Model\City;

final class CityNotFound extends \Exception
{
    public static function named($city)
    {
        return new self(sprintf('City "%s" not found', $city));
    }
}
