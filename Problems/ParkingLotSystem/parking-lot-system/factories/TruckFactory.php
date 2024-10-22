<?php
namespace Factories;

use Models\Truck;

/**
 * Factory for creating truck vehicles.
 */
class TruckFactory extends VehicleFactory {
    public function create_vehicle(): Truck {
        return new Truck();
    }
}
