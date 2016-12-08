<?php
declare(strict_types=1);

namespace Domain\Model\Event;

final class Venue
{
    /** @var string */
    private $name;
    /** @var string */
    private $address;
    /** @var string */
    private $city;
    /** @var string */
    private $country;

    public function __construct($name, $address, $city, $country)
    {
        $this->name = $name;
        $this->address = $address;
        $this->city = $city;
        $this->country = $country;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function getLon()
    {
        return $this->lon;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getCountry()
    {
        return $this->country;
    }
}
