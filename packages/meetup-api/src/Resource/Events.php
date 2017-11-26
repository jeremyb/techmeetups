<?php

declare(strict_types=1);

namespace Meetup\Resource;

use Http\Client\Common\HttpMethodsClient;
use Meetup\DTO\Event;
use Meetup\DTO\Query\FindUpcomingEventsQuery;
use Meetup\DTO\Query\GroupEventsQuery;
use Meetup\Http\ResponseConverter;
use Webmozart\Assert\Assert;
use Zend\Hydrator\HydratorInterface;

class Events
{
    /** @var HttpMethodsClient */
    private $httpClient;
    /** @var HydratorInterface */
    private $hydrator;

    public function __construct(
        HttpMethodsClient $httpClient,
        HydratorInterface $hydrator
    ) {
        $this->httpClient = $httpClient;
        $this->hydrator = $hydrator;
    }

    public function findUpcomingEvents(FindUpcomingEventsQuery $query) : array
    {
        $params = array_filter($this->hydrator->extract($query), function ($item) {
            return null !== $item;
        });
        $response = $this->httpClient->get(
            sprintf('/find/upcoming_events?%s', http_build_query($params))
        );
        $data = ResponseConverter::convert($response);

        Assert::keyExists($data, 'events');

        return array_map(function (array $data) {
            return Event::fromData($data);
        }, $data['events']);
    }

    public function ofGroup(string $group, GroupEventsQuery $query) : array
    {
        $params = array_filter($this->hydrator->extract($query), function ($item) {
            return null !== $item;
        });
        $response = $this->httpClient->get(
            sprintf('/%s/events?%s', $group, http_build_query($params))
        );
        $data = ResponseConverter::convert($response);

        return array_map(function (array $data) {
            return Event::fromData($data);
        }, $data);
    }
}
