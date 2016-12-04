<?php

namespace spec\Application\Event;

use Application\Event\EventProvider;
use Application\Event\Synchronizer;
use Domain\Model\City\City;
use Domain\Model\City\CityConfiguration;
use Domain\Model\City\CityConfigurationRepository;
use Domain\Model\Event\Event;
use Domain\Model\Event\EventRepository;
use PhpSpec\ObjectBehavior;

class SynchronizerSpec extends ObjectBehavior
{
    function let(
        CityConfigurationRepository $cityConfigurationRepository,
        EventProvider $provider,
        EventRepository $eventRepository
    ) {
        $this->beConstructedWith(
            $cityConfigurationRepository,
            $provider,
            $eventRepository
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
            $event = Event::named('Sample event'),
        ]);

        $eventRepository->add($event)->shouldBeCalled();

        $this->synchronize();
    }
}
