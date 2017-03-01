<?php

declare(strict_types=1);

namespace Domain\Model\Event;

final class Venue
{
    /** @var string */
    private $name;
    /** @var float */
    public $lat;
    /** @var float */
    public $lon;
    /** @var string */
    private $address;
    /** @var string */
    private $city;
    /** @var string */
    private $country;

    public function __construct(
        ?string $name,
        ?float $lat,
        ?float $lon,
        ?string $address,
        ?string $city,
        ?string $country)
    {
        $this->name = $name;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->address = $address;
        $this->city = $city;
        $this->country = $country;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function getLat() : ?float
    {
        return $this->lat;
    }

    public function getLon() : ?float
    {
        return $this->lon;
    }

    public function getAddress() : ?string
    {
        return $this->address;
    }

    public function getCity() : ?string
    {
        return $this->city;
    }

    public function getCountry() : ?string
    {
        return $this->country;
    }
}
