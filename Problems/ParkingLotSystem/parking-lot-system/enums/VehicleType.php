<?php
namespace Enums;

/**
 * Enum for vehicle types.
 */
class VehicleType {
	const CAR       = 'Car';
	const TRUCK     = 'Truck';
	const ELECTRIC  = 'Electric Car';
	const VAN       = 'Van';
	const MOTORBIKE = 'Motorbike';

	public static function can_fit_in_spot( $vehicle_type, $spot_type ) {
		switch ( $vehicle_type ) {
			case self::TRUCK:
			case self::VAN:
				return $spot_type === ParkingSpotType::LARGE;
			case self::MOTORBIKE:
				return $spot_type === ParkingSpotType::MOTORBIKE;
			case self::CAR:
				return in_array( $spot_type, array( ParkingSpotType::COMPACT, ParkingSpotType::LARGE ) );
			case self::ELECTRIC:
				return in_array( $spot_type, array( ParkingSpotType::ELECTRIC, ParkingSpotType::COMPACT, ParkingSpotType::LARGE ) );
			default:
				return false;
		}
	}
}
