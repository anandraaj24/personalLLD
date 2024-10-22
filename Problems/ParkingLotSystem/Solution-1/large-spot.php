<?php
/**
 * Class representing a large parking spot.
 */
class LargeSpot extends ParkingSpot {
    public function __construct() {
        parent::__construct(ParkingSpotType::LARGE);
    }
}
