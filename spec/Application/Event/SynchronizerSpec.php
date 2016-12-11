<?php

namespace spec\Application\Event;

use Application\Event\DTO\EventDTO;
use Application\Event\EventProvider;
use Application\Event\Synchronizer;
use Domain\Model\City\City;
use Domain\Model\City\CityConfiguration;
use Domain\Model\City\CityConfigurationRepository;
use Domain\Model\Event\Event;
use Domain\Model\Event\EventRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

class SynchronizerSpec extends ObjectBehavior
{
    function let(
        CityConfigurationRepository $cityConfigurationRepository,
        EventProvider $provider,
        EventRepository $eventRepository,
        LoggerInterface $logger
    ) {
        $this->beConstructedWith(
            $cityConfigurationRepository,
            $provider,
            $eventRepository,
            $logger
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Synchronizer::class);
    }

    function it_should_synchronize(
        CityConfigurationRepository $cityConfigurationRepository,
        EventProvider $provider,
        EventRepository $eventRepository
    ) {
        $cityConfigurationRepository->findAll()->shouldBeCalled()->willReturn([
            new CityConfiguration(
                City::named('Montpellier'),
                ['Montpellier-PHP-Meetup']
            ),
        ]);

        $provider->getEvents(['Montpellier-PHP-Meetup'])->shouldBeCalled()->willReturn([
            EventDTO::fromData([
                'provider_id' => '123',
                'name' => 'First event',
                'description' => 'lorem ipsum',
                'link' => 'https://www.meetup.com/',
                'duration' => 120,
                'planned_at' => new \DateTimeImmutable(),
                'venue_city' => 'Montpellier',
                'group_name' => 'AFUP Montpellier',
            ])
        ]);

        $eventRepository->add(Argument::type(Event::class))->shouldBeCalled();

        $this->synchronize();
    }
}
