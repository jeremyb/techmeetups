<?php
declare(strict_types=1);

namespace Infrastructure\Persistence\InMemory;

use Domain\Model\City\City;
use Webmozart\Assert\Assert;

final class InMemoryCityRepositoryFactory
{
    public static function create(array $config)
    {
        $cities = array_map(function ($cityConfig) {
            Assert::keyExists($cityConfig, 'city');

            return City::named($cityConfig['city']);
        }, $config);

        return new InMemoryCityRepository($cities);
    }
}
