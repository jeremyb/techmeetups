<?php

declare(strict_types=1);

namespace Infrastructure\Symfony;

use Infrastructure\Symfony\DependencyInjection\UIExtension;
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Bundle\WebServerBundle\WebServerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles() : array
    {
        $bundles = [
            new FrameworkBundle(),
            new TwigBundle(),
            new MonologBundle(),
        ];

        if (\in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new DebugBundle();
            $bundles[] = new WebProfilerBundle();

            if ('dev' === $this->getEnvironment()) {
                $bundles[] = new WebServerBundle();
            }
        }

        return $bundles;
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        if ($this->isDebug()) {
            $routes->import('@WebProfilerBundle/Resources/config/routing/wdt.xml', '/_wdt');
            $routes->import('@WebProfilerBundle/Resources/config/routing/profiler.xml', '/_profiler');
            $routes->import('@TwigBundle/Resources/config/routing/errors.xml', '/_error');
        }

        $routes->import(__DIR__.'/Resources/config/routing.yml');
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $container->setParameter('container.autowiring.strict_mode', true);
        $container->setParameter('container.dumper.inline_class_loader', true);

        $loader->load(sprintf('%s/Resources/config/config_%s.yml', __DIR__, $this->getEnvironment()));

        $container->registerExtension(new UIExtension());
        $loader->load($this->getRootDir().'/config/meetups.yml');
    }

    public function getName() : string
    {
        return 'ui';
    }

    public function getRootDir() : string
    {
        return __DIR__.'/../../..';
    }

    public function getCacheDir() : string
    {
        return $this->getProjectDir().'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir() : string
    {
        return $this->getProjectDir().'/var/logs';
    }
}
