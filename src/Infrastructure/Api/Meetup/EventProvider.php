<?php

declare(strict_types=1);

namespace Infrastructure\Api\Meetup;

use Application\EventProvider as EventProviderInterface;
use Domain\Model\City\City;
use Domain\Model\Event\Events;
use Meetup\DTO\Event;
use Meetup\DTO\EventVisibility;
use Meetup\DTO\Group;
use Meetup\DTO\Query\FindGroupsQuery;
use Meetup\DTO\Query\FindUpcomingEventsQuery;
use Meetup\DTO\Query\GroupEventsQuery;
use Meetup\Http\RateLimitExceeded;
use Meetup\Meetup;
use Psr\Log\LoggerInterface;

final class EventProvider implements EventProviderInterface
{
    private const TECH_CATEGORY_ID = 34;
    private const TECH_TOPIC_ID = 292;

    /** @var Meetup */
    private $meetup;
    /** @var LoggerInterface */
    private $logger;

    public function __construct(Meetup $meetup, LoggerInterface $logger)
    {
        $this->meetup = $meetup;
        $this->logger = $logger;
    }

    public function importPastEvents(City $city) : Events
    {
        /** @var array $groups */
        $groups = $this->retry(function () use ($city) {
            return $this->meetup->groups()->findGroups(
                FindGroupsQuery::from([
                    'location' => $city->getName(),
                    'radius' => 0,
                    'category' => self::TECH_CATEGORY_ID,
                ])
            );
        });

        $events = [[]];
        /** @var Group $group */
        foreach ($groups as $group) {
            $eventsOfGroup = $this->retry(function () use ($group) {
                $events = $this->meetup->events()->ofGroup(
                    $group->urlname,
                    GroupEventsQuery::from([
                        'status' => GroupEventsQuery::PAST,
                    ])
                );

                return array_filter($events, function (Event $event) {
                    return EventVisibility::PUBLIC_LIMITED !== $event->visibility->value;
                });
            });

            $events[] = array_map(function (Event $event) use ($city) {
                return EventFactory::create($city, $event);
            }, $eventsOfGroup);
        }

        return new Events(...array_merge(...$events));
    }

    public function getUpcomingEvents(City $city) : Events
    {
        $events = $this->retry(function () use ($city) {
            $events = $this->meetup->events()->findUpcomingEvents(
                FindUpcomingEventsQuery::from([
                    'lat' => $city->getLat(),
                    'lon' => $city->getLon(),
                    'radius' => 'smart',
                    'order' => 'time',
                    'topic_category' => self::TECH_TOPIC_ID,
                    'page' => 100,
                ])
            );

            return array_filter($events, function (Event $event) {
                return EventVisibility::PUBLIC_LIMITED !== $event->visibility->value;
            });
        });

        return new Events(
            ...array_map(function (Event $event) use ($city) {
                return EventFactory::create($city, $event);
            }, $events)
        );
    }

    private function retry(callable $fn)
    {
        beginning:
        try {
            return $fn();
        } catch (RateLimitExceeded $e) {
            $this->logger->info($e->getMessage());
            $this->logger->info(sprintf('Waiting: %d second(s)', $e->getRateLimitReset()));
            sleep($e->getRateLimitReset());

            goto beginning;
        }
    }
}
