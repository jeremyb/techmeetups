<?php

declare(strict_types=1);

namespace Meetup;

use Http\Client\Common\HttpMethodsClient;
use Meetup\Resource\Events;
use Meetup\Resource\Groups;

interface Meetup
{
    public const BASE_URL = 'https://api.meetup.com';

    public function events() : Events;

    public function groups() : Groups;

    public function getHttpClient() : HttpMethodsClient;
}
