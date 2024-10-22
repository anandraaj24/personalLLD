<?php
/**
 * Class representing a Truck.
 */
class Truck extends Vehicle {
    public function __construct() {
        parent::__construct(VehicleType::TRUCK);
    }
}
