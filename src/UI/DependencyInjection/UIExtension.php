<?php

namespace UI\DependencyInjection;

use Domain\Model\City\City;
use Domain\Model\City\CityConfiguration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class UIExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('actions.yml');
        $loader->load('services.yml');

        $citiesConfiguration = [];
        foreach ($config['cities'] as $cityConfig) {
            $cityDefinition = (new Definition())
                ->setClass(City::class)
                ->setFactory([City::class, 'named'])
                ->addArgument($cityConfig['city'])
                ->setPublic(false);

            $configurationDefinition = (new Definition())
                ->setClass(CityConfiguration::class)
                ->addArgument($cityDefinition)
                ->addArgument(array_shift($cityConfig['providers']))
                ->setPublic(false);

            $citiesConfiguration[] = $configurationDefinition;
        }

//        $citiesConfiguration = [];
//        foreach ($config['cities'] as $cityConfig) {
//            $citiesConfiguration[] = new CityConfiguration(
//                City::named($cityConfig['city']),
//                array_shift($cityConfig['providers'])
//            );
//        }

        $container
            ->getDefinition('app.city_configuration')
            ->replaceArgument(0, $citiesConfiguration);
    }

    public function getAlias()
    {
        return 'techmeetups';
    }
}
