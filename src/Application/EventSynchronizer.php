<?php

declare(strict_types=1);

namespace Application;

use Domain\Model\City\CityConfigurationRepository;
use Domain\Model\Event\EventId;
use Domain\Model\Event\EventRepository;
use Psr\Log\LoggerInterface;

final class EventSynchronizer
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

    public function synchronize() : int
    {
        $imported = 0;
        foreach ($this->cityConfigurationRepository->findAll() as $cityConfiguration) {
            $city = $cityConfiguration->getCity();
            $this->logger->info(sprintf('City: %s', (string) $city));

            $eventsDto = $this->provider->getUpcomingEvents($cityConfiguration);

            foreach ($eventsDto as $eventDto) {
                $eventId = EventId::fromString($eventDto->providerId);

                if ($this->eventRepository->contains($eventId)) {
                    $event = EventFactory::create($eventDto, $city);
                    $this->eventRepository->update($event);

                    $this->logger->info(sprintf('Event updated on group "%s": %s',
                        $event->getGroup()->getName(),
                        $event->getName()
                    ));

                    continue;
                }

                $event = EventFactory::create($eventDto, $city);
                $this->eventRepository->add($event);

                $this->logger->info(sprintf('New event on group "%s": %s',
                    $event->getGroup()->getName(),
                    $event->getName()
                ));
                ++$imported;
            }
        }

        return $imported;
    }
}
