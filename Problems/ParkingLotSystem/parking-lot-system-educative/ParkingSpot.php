<?php

require_once 'Enums.php'; // Assuming constants.php contains the necessary enums

abstract class ParkingSpot {
    private $number;
    private $free;
    private $vehicle;
    private $parkingSpotType;

    public function __construct($number, $parkingSpotType) {
        $this->number = $number;
        $this->free = true;
        $this->vehicle = null;
        $this->parkingSpotType = $parkingSpotType;
    }

    public function isFree() {
        return $this->free;
    }

    public function assignVehicle($vehicle) {
        $this->vehicle = $vehicle;
        $this->free = false;
    }

    public function removeVehicle() {
        $this->vehicle = null;
        $this->free = true; // Corrected the property name
    }
}

class HandicappedSpot extends ParkingSpot {
    public function __construct($number) {
        parent::__construct($number, ParkingSpotType::HANDICAPPED);
    }
}

class CompactSpot extends ParkingSpot {
    public function __construct($number) {
        parent::__construct($number, ParkingSpotType::COMPACT);
    }
}

class LargeSpot extends ParkingSpot {
    public function __construct($number) {
        parent::__construct($number, ParkingSpotType::LARGE);
    }
}

class MotorbikeSpot extends ParkingSpot {
    public function __construct($number) {
        parent::__construct($number, ParkingSpotType::MOTORBIKE);
    }
}

class ElectricSpot extends ParkingSpot {
    public function __construct($number) {
        parent::__construct($number, ParkingSpotType::ELECTRIC);
    }
}

?>
