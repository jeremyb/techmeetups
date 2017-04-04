<?php

declare(strict_types=1);

namespace Meetup;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Meetup\Resource\Events;

class Meetup
{
    const BASE_URL = 'https://api.meetup.com';

    /** @var HttpClient */
    private $httpClient;
    /** @var RequestFactory */
    private $requestFactory;
    /** @var null|HttpMethodsClient */
    private $httpMethodsClient;

    public function __construct(
        HttpClient $httpClient,
        RequestFactory $requestFactory
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
    }

    public function events(): Events
    {
        return new Events($this->getHttpClient());
    }

    public function getHttpClient(): HttpMethodsClient
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
