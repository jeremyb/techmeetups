<?php

declare(strict_types=1);

namespace Meetup;

use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Meetup\Http\Plugin\AddApiKeyPlugin;
use Meetup\Http\Plugin\LoggerPlugin;
use Meetup\Http\Plugin\RateLimitExceededPlugin;
use Meetup\Hydrator\HydratorFactory;
use Psr\Log\LoggerInterface;

final class MeetupApiKeyFactory
{
    public static function create(string $key, LoggerInterface $logger) : Meetup
    {
        $httpClient = HttpClientDiscovery::find();
        $messageFactory = MessageFactoryDiscovery::find();

        return new MeetupClient(
            new PluginClient($httpClient, [
                new AddHostPlugin(
                    UriFactoryDiscovery::find()->createUri(Meetup::BASE_URL)
                ),
                new HeaderDefaultsPlugin([
                    'Accept' => 'application/json',
                    'User-Agent' => 'TechMeetups',
                ]),
                new AddApiKeyPlugin($key),
                new LoggerPlugin($logger),
                new ErrorPlugin(),
                new RateLimitExceededPlugin(),
            ]),
            $messageFactory,
            HydratorFactory::create()
        );
    }

    private function __construct()
    {
    }
}
