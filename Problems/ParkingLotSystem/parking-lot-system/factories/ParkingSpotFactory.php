<?php
namespace Factories;

use Models\ParkingSpot;

/**
 * Abstract factory for creating parking spots.
 */
abstract class ParkingSpotFactory {
	abstract public function create_parking_spot(): ParkingSpot;
}
