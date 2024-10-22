<?php
namespace Models;

/**
 * Class representing a compact parking spot.
 */
class CompactSpot extends ParkingSpot {
    public function __construct() {
        parent::__construct("Compact");
    }
}
