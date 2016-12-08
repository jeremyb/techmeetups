<?php
declare(strict_types=1);

namespace Meetup\Resource;

use Http\Client\Common\HttpMethodsClient;
use Meetup\DTO\Event;
use Meetup\Http\ResponseConverter;

final class Events
{
    /** @var HttpMethodsClient */
    private $httpClient;

    public function __construct(HttpMethodsClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $group
     *
     * @return Event[]
     */
    public function ofGroup(string $group): array
    {
        $response = $this->httpClient->get(sprintf('%s/events', $group));
        $eventsData = ResponseConverter::convert($response);

        return array_map(function ($data) {
            return Event::fromData($data);
        }, $eventsData);
    }
}
