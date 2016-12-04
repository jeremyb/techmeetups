<?php
declare(strict_types=1);

namespace Application\City;

use Domain\Model\City\CityRepository;
use Domain\Model\City\CityConfiguration;
use Infrastructure\Persistence\InMemory\InMemoryCityConfigurationRepository;
use Webmozart\Assert\Assert;

final class CityConfigurationRepositoryFactory
{
    public static function create(CityRepository $cityRepository, array $config)
    {
        $citiesConfiguration = [];
        foreach ($config as $cityConfig) {
            Assert::keyExists($cityConfig, 'name');
            Assert::keyExists($cityConfig, 'providers');
            Assert::keyExists($cityConfig['providers'], 'meetup.com');

            $city = $cityRepository->ofName($cityConfig['name']);

            $citiesConfiguration[] = new CityConfiguration(
                $city,
                $cityConfig['providers']['meetup.com']
            );
        }

        return new InMemoryCityConfigurationRepository($citiesConfiguration);
    }
}
