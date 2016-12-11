<?php
declare(strict_types=1);

namespace Application\Event;

use Domain\Model\City\CityConfigurationRepository;
use Domain\Model\Event\Event;
use Domain\Model\Event\EventRepository;
use Domain\Model\Event\Group;
use Domain\Model\Event\Venue;
use Psr\Log\LoggerInterface;

final class Synchronizer
{
    /** @var CityConfigurationRepository */
    private $cityConfigurationRepository;
    /** @var EventProvider */
    private $provider;
    /** @var EventRepository */
    private $eventRepository;
    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        CityConfigurationRepository $cityConfigurationRepository,
        EventProvider $provider,
        EventRepository $eventRepository,
        LoggerInterface $logger
    ) {
        $this->cityConfigurationRepository = $cityConfigurationRepository;
        $this->provider = $provider;
        $this->eventRepository = $eventRepository;
        $this->logger = $logger;
    }

    public function synchronize()
    {
        foreach ($this->cityConfigurationRepository->findAll() as $cityConfiguration) {
            $this->logger->info(
                sprintf('City: %s', (string) $cityConfiguration->getCity())
            );

            $eventsDto = $this->provider->getEvents(
                $cityConfiguration->getMeetups()
            );

            foreach ($eventsDto as $eventDto) {
                if (null !== $eventDto->venueAddress) {
                    $venue = new Venue(
                        $eventDto->venueName,
                        $eventDto->venueAddress,
                        $eventDto->venueCity,
                        $eventDto->venueCountry
                    );
                }

                if (null !== $eventDto->groupName) {
                    $group = new Group($eventDto->groupName);
                }

                $event = Event::create(
                    uniqid(),
                    $cityConfiguration->getCity(),
                    $eventDto->name,
                    $eventDto->description,
                    $eventDto->link,
                    $eventDto->duration,
                    $eventDto->plannedAt,
                    $venue ?? null,
                    $group ?? null
                );

                $this->eventRepository->add($event);
            }
        }
    }
}
