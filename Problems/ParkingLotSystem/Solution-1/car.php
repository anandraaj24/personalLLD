<?php
/**
 * Class representing a Car.
 */
class Car extends Vehicle {
    public function __construct() {
        parent::__construct(VehicleType::CAR);
    }
}
