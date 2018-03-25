<?php

declare(strict_types=1);

namespace Infrastructure\Rest;

use Domain\ReadModel\EventFinder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

final class GetEvents
{
    /** @var EventFinder */
    private $eventFinder;
    /** @var SerializerInterface */
    private $serializer;

    public function __construct(EventFinder $eventFinder, SerializerInterface $serializer)
    {
        $this->eventFinder = $eventFinder;
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request) : JsonResponse
    {
        if ($request->query->has('group_ids')) {
            $groupIds = mb_split(',', $request->query->get('group_ids'));
            $events = $this->eventFinder->findNextEventsOfGroups($groupIds);
        } else {
            $events = $this->eventFinder->findNextEvents();
        }

        return JsonResponse::fromJsonString(
            $this->serializer->serialize(['events' => $events], 'json')
        );
    }
}
