<?php

declare(strict_types=1);

namespace Domain\Model\City;

final class City
{
    /** @var string */
    private $id;
    /** @var string */
    private $name;
    /** @var float */
    private $lat;
    /** @var float */
    private $lon;

    public static function named(string $name, float $lat, float $lon) : City
    {
        return new self(strtolower($name), $name, $lat, $lon);
    }

    private function __construct(string $id, string $name, float $lat, float $lon)
    {
        $this->id = $id;
        $this->name = $name;
        $this->lat = $lat;
        $this->lon = $lon;
    }

    public function __toString() : string
    {
        return $this->name;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getLat() : float
    {
        return $this->lat;
    }

    public function getLon() : float
    {
        return $this->lon;
    }
}
