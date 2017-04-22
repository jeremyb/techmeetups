<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\InMemory;

use Domain\Model\City\CityConfiguration;
use Domain\Model\City\CityRepository;
use Webmozart\Assert\Assert;

final class InMemoryCityConfigurationRepositoryFactory
{
    public static function create(
        CityRepository $cityRepository,
        array $config
    ) : InMemoryCityConfigurationRepository {
        $citiesConfiguration = array_map(function ($cityConfig) use ($cityRepository) {
            Assert::keyExists($cityConfig, 'city');
            Assert::keyExists($cityConfig, 'providers');
            Assert::keyExists($cityConfig['providers'], 'meetup.com');

            $city = $cityRepository->ofName($cityConfig['city']);

            return new CityConfiguration(
                $city,
                // @todo improve this part to handle different providers?
                ...$cityConfig['providers']['meetup.com']
            );
        }, $config);

        return new InMemoryCityConfigurationRepository(...$citiesConfiguration);
    }
}
