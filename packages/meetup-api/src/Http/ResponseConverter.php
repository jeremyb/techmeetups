<?php
declare(strict_types=1);

namespace Meetup\Http;

use Psr\Http\Message\ResponseInterface;

abstract class ResponseConverter
{
    public static function convert(ResponseInterface $response): array
    {
        if (0 !== strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
            throw new \InvalidArgumentException('Not a valid JSON response.');
        }

        $json = json_decode((string) $response->getBody(), true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(
                'json_decode error: ' . json_last_error_msg()
            );
        }

        return $json;
    }
}
