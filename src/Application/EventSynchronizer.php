<?php

declare(strict_types=1);

namespace Application;

use Domain\Model\City\Cities;
use Domain\Model\Event\Event;
use Domain\Model\Event\EventRepository;
use Domain\Model\Event\GroupRepository;
use Psr\Log\LoggerInterface;

final class EventSynchronizer
{
    /** @var Cities */
    private $cities;
    /** @var EventProvider */
    private $provider;
    /** @var GroupRepository */
    private $groupRepository;
    /** @var EventRepository */
    private $eventRepository;
    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        Cities $cities,
        EventProvider $provider,
        GroupRepository $groupRepository,
        EventRepository $eventRepository,
        LoggerInterface $logger
    ) {
        $this->cities = $cities;
        $this->provider = $provider;
        $this->groupRepository = $groupRepository;
        $this->eventRepository = $eventRepository;
        $this->logger = $logger;
    }

    public function synchronize() : int
    {
        $imported = 0;
        foreach ($this->cities as $city) {
            $this->logger->info(sprintf('City: %s', (string) $city));

            $events = $this->provider->getUpcomingEvents($city);
            /** @var Event $event */
            foreach ($events as $event) {
                $this->groupRepository->addOrUpdate($event->getGroup());

                if ($this->eventRepository->contains($event->getId())) {
                    $this->eventRepository->update($event);

                    $this->logger->info(sprintf('Event updated on group "%s": %s',
                        $event->getGroup()->getName(),
                        $event->getName()
                    ));

                    continue;
                }

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
