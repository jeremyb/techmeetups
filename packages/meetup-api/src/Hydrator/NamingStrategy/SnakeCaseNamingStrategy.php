<?php

declare(strict_types=1);

namespace Meetup\Hydrator\NamingStrategy;

use Doctrine\Common\Inflector\Inflector;
use Zend\Hydrator\NamingStrategy\NamingStrategyInterface;

final class SnakeCaseNamingStrategy implements NamingStrategyInterface
{
    public function hydrate($name)
    {
        return Inflector::camelize($name);
    }

    public function extract($name)
    {
        return Inflector::tableize($name);
    }
}
