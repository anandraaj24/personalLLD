<?php
namespace Models;

/**
 * Class representing a handicapped parking spot.
 */
class HandicappedSpot extends ParkingSpot {
	public function __construct() {
		parent::__construct( 'Handicapped' );
	}
}
