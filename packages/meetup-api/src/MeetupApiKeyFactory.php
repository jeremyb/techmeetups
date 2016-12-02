<?php
declare(strict_types=1);

namespace Meetup;

use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Meetup\Http\Plugin\AddApiKeyPlugin;

abstract class MeetupApiKeyFactory
{
    public static function create(string $key): Meetup
    {
        $httpClient = HttpClientDiscovery::find();
        $messageFactory = MessageFactoryDiscovery::find();

        return new Meetup(
            new PluginClient($httpClient, [
                new AddHostPlugin(
                    UriFactoryDiscovery::find()->createUri(Meetup::BASE_URL)
                ),
                new HeaderDefaultsPlugin([
                    'Accept' => 'application/json',
                    'User-Agent' => 'TechMeetups',
                ]),
                new AddApiKeyPlugin($key),
            ]),
            $messageFactory
        );
    }
}
