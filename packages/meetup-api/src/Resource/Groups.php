<?php

declare(strict_types=1);

namespace Meetup\Resource;

use Http\Client\Common\HttpMethodsClient;
use Meetup\DTO\Group;
use Meetup\DTO\Query\FindGroupsQuery;
use Meetup\Http\ResponseConverter;
use Zend\Hydrator\HydratorInterface;

class Groups
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

    public function findGroups(FindGroupsQuery $query) : array
    {
        $params = array_filter($this->hydrator->extract($query), function ($item) {
            return null !== $item;
        });
        $response = $this->httpClient->get(
            sprintf('/find/groups?%s', http_build_query($params))
        );
        $data = ResponseConverter::convert($response);

        return array_map(function (array $data) {
            return Group::fromData($data);
        }, $data);
    }
}
