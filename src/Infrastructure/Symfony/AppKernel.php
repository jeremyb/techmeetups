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
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles() : array
    {
        $bundles = [
            new FrameworkBundle(),
            new TwigBundle(),
            new MonologBundle(),
        ];

        if ($this->isDebug()) {
            $bundles[] = new DebugBundle();
            $bundles[] = new WebProfilerBundle();
        }

        return $bundles;
    }

    protected function configureRoutes(RouteCollectionBuilder $routes) : void
    {
        if ($this->isDebug()) {
            $routes->import('@WebProfilerBundle/Resources/config/routing/wdt.xml', '/_wdt');
            $routes->import('@WebProfilerBundle/Resources/config/routing/profiler.xml', '/_profiler');
        }

        $routes->import(__DIR__ . '/Resources/config/routing.yml');
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader) : void
    {
        $c->loadFromExtension('framework', [
            'secret' => 'S0ME_SECRET',
            'templating' => ['engines' => ['twig']],
            'profiler' => [
                'enabled' => $this->isDebug(),
                'only_exceptions' => false,
                'dsn' => 'file:%kernel.cache_dir%/profiler',
            ],
        ]);

        if ('test' === $this->getEnvironment()) {
            $c->prependExtensionConfig('framework', [
                'test' => null,
            ]);
        }

        $c->loadFromExtension('twig', [
            'debug' => true,
            'paths' => [
                __DIR__ . '/../../UI/templates' => '__main__',
            ],
            'date' => [
                'timezone' => 'Europe/Paris',
            ]
        ]);

        if ('prod' === $this->getEnvironment()) {
            $c->loadFromExtension('monolog', [
                'handlers' => [
                    'main' => [
                        'type' => 'error_log',
                        'level' => 'warning',
                    ],
                ],
            ]);
        } else {
            $c->loadFromExtension('monolog', [
                'handlers' => [
                    'main' => [
                        'type' => 'stream',
                        'path' => '%kernel.logs_dir%/%kernel.environment%.log',
                        'level' => 'debug',
                        'channels' => ['!event'],
                    ],
                    'console' => [
                        'type' => 'console',
                        'channels' => ['!event', '!doctrine', '!console'],
                    ],
                ],
            ]);
        }

        $c->registerExtension(new UIExtension());

        $loader->load($this->getRootDir().'/config/meetups.yml');

        if (isset($this->bundles['WebProfilerBundle'])) {
            $c->loadFromExtension('web_profiler', array(
                'toolbar' => true,
                'intercept_redirects' => false,
            ));
        }
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
        return $this->getRootDir().'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir() : string
    {
        return $this->getRootDir().'/var/logs';
    }
}
