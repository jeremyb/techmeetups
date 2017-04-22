<?php

declare(strict_types=1);

namespace Meetup\Resource;

use Http\Client\Common\HttpMethodsClient;
use Meetup\DTO\Event;
use Meetup\Http\ResponseConverter;

class Events
{
    public const CANCELLED = 'cancelled';
    public const DRAFT = 'draft';
    public const PAST = 'past';
    public const PROPOSED = 'proposed';
    public const SUGGESTED = 'suggested';
    public const UPCOMING = 'upcoming';

    /** @var HttpMethodsClient */
    private $httpClient;

    public function __construct(HttpMethodsClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function ofGroup(string $group, string $status = self::UPCOMING): array
    {
        $response = $this->httpClient->get(
            sprintf('/%s/events?status=%s', $group, $status)
        );
        $eventsData = ResponseConverter::convert($response);

        return array_map(function ($data) {
            return Event::fromData($data);
        }, $eventsData);
    }
}
