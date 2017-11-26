<?php

declare(strict_types=1);

namespace Infrastructure\Symfony\Factory;

use Domain\Model\City\Cities;
use Domain\Model\City\City;
use Webmozart\Assert\Assert;

final class CitiesFactory
{
    public static function create(array $data) : Cities
    {
        return new Cities(
            ...array_map(function (array $data) {
                Assert::keyExists($data, 'name');
                Assert::keyExists($data, 'lat');
                Assert::keyExists($data, 'lon');

                return City::named(
                    $data['name'],
                    (float) $data['lat'],
                    (float) $data['lon']
                );
            }, $data)
        );
    }

    private function __construct()
    {
    }
}
