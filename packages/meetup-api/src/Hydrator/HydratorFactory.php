<?php

declare(strict_types=1);

namespace Meetup\Hydrator;

use Meetup\Hydrator\NamingStrategy\SnakeCaseNamingStrategy;
use Zend\Hydrator\HydratorInterface;
use Zend\Hydrator\ObjectProperty;

final class HydratorFactory
{
    public static function create() : HydratorInterface
    {
        $hydrator = new ObjectProperty();
        $hydrator->setNamingStrategy(new SnakeCaseNamingStrategy());

        return $hydrator;
    }
}
