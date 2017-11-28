<?php

declare(strict_types=1);

namespace Meetup;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Meetup\Resource\Events;
use Meetup\Resource\Groups;
use Zend\Hydrator\HydratorInterface;

final class MeetupClient implements Meetup
{
    /** @var HttpClient */
    private $httpClient;
    /** @var RequestFactory */
    private $requestFactory;
    /** @var null|HttpMethodsClient */
    private $httpMethodsClient;
    /** @var HydratorInterface */
    private $hydrator;

    public function __construct(
        HttpClient $httpClient,
        RequestFactory $requestFactory,
        HydratorInterface $hydrator
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->hydrator = $hydrator;
    }

    public function events() : Events
    {
        return new Events($this->getHttpClient(), $this->hydrator);
    }

    public function groups() : Groups
    {
        return new Groups($this->getHttpClient(), $this->hydrator);
    }

    public function getHttpClient() : HttpMethodsClient
    {
        if (null !== $this->httpMethodsClient) {
            return $this->httpMethodsClient;
        }

        $this->httpMethodsClient = new HttpMethodsClient(
            $this->httpClient,
            $this->requestFactory
        );

        return $this->httpMethodsClient;
    }
}
