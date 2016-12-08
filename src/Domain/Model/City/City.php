<?php
declare(strict_types=1);

namespace Domain\Model\City;

final class City
{
    /** @var string */
    private $id;
    /** @var string */
    private $name;

    public static function named(string $name): City
    {
        return new self(strtolower($name), $name);
    }

    private function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
}
