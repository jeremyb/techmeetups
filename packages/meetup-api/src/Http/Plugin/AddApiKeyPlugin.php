<?php

declare(strict_types=1);

namespace Meetup\Http\Plugin;

use Http\Client\Common\Plugin;
use Psr\Http\Message\RequestInterface;

final class AddApiKeyPlugin implements Plugin
{
    /** @var string */
    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        $request->withUri(
            $request->getUri()->withQuery(sprintf('key=%s', $this->key))
        );

        return $next($request);
    }
}
