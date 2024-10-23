<?php
namespace Enums;

/**
 * Enum for parking spot types.
 */
class ParkingSpotType {
	const HANDICAPPED = 'Handicapped';
	const COMPACT     = 'Compact';
	const LARGE       = 'Large';
	const MOTORBIKE   = 'Motorbike';
	const ELECTRIC    = 'Electric Charging';

	public static function is_suitable_for( $spot_type, $vehicle_type ) {
		return VehicleType::can_fit_in_spot( $vehicle_type, $spot_type );
	}
}
