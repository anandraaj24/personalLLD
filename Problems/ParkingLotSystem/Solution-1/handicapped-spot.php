<?php
/**
 * Class representing a handicapped parking spot.
 */
class HandicappedSpot extends ParkingSpot {
    public function __construct() {
        parent::__construct(ParkingSpotType::HANDICAPPED);
    }
}
