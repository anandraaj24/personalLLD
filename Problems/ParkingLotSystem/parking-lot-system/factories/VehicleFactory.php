<?php
namespace Factories;

use Models\Vehicle;

/**
 * Abstract factory for creating vehicles.
 */
abstract class VehicleFactory {
	abstract public function create_vehicle(): Vehicle;
}
