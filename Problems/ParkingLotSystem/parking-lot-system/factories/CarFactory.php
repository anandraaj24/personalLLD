<?php
namespace Factories;

use Models\Car;

/**
 * Factory for creating car vehicles.
 */
class CarFactory extends VehicleFactory {
    public function create_vehicle(): Car {
        return new Car();
    }
}
