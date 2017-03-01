<?php

declare(strict_types=1);

namespace Domain\Model\City;

final class CityNotFound extends \LogicException
{
    public static function named(string $city) : self
    {
        return new self(sprintf('City "%s" not found', $city));
    }
}
