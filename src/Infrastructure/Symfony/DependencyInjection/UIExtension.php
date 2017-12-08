<?php

declare(strict_types=1);

namespace Infrastructure\Symfony\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class UIExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container) : void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('app.techmeetups.cities', $config['cities']);
    }

    public function getAlias() : string
    {
        return 'techmeetups';
    }
}
