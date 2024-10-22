<?php
namespace Models;

/**
 * Class representing a parking floor.
 */
class ParkingFloor {
    private $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function get_name() {
        return $this->name;
    }
}
