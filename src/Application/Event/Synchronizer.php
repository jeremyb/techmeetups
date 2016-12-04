<?php
declare(strict_types=1);

namespace Application\Event;

use Domain\Model\City\CityConfigurationRepository;
use Domain\Model\Event\EventRepository;

final class Synchronizer
{
    /** @var CityConfigurationRepository */
    private $cityConfigurationRepository;
    /** @var EventProvider */
    private $provider;
    /** @var EventRepository */
    private $eventRepository;

    public function __construct(
        CityConfigurationRepository $cityConfigurationRepository,
        EventProvider $provider,
        EventRepository $eventRepository
    ) {
        $this->cityConfigurationRepository = $cityConfigurationRepository;
        $this->provider = $provider;
        $this->eventRepository = $eventRepository;
    }

    public function synchronize()
    {
        foreach ($this->cityConfigurationRepository->findAll() as $cityConfiguration) {
            $events = $this->provider->getEvents(
                $cityConfiguration->getMeetups()
            );

            foreach ($events as $event) {
                $this->eventRepository->add($event);
            }
        }
    }
}
