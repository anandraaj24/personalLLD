<?php
/**
 * File for parking lot.
 *
 * @package ParkingLotSystem
 */

namespace Models;

use Exceptions\ParkingFullException;
use Enums\VehicleType;

/**
 * Class for parking lot singleton.
 */
class ParkingLot {
	/**
	 * Holds the single instance.
	 *
	 * @var ParkingLot Holds the instance of this class.
	 */
	private static $instance;

	/**
	 * Holds all the tickets.
	 *
	 * @var array Holds all the tickets of parking lot.
	 */
	private array $parking_tickets = array();

	/**
	 * Holds all the numbers of parking floors.
	 *
	 * @var array Holds all the number of parking floors.
	 */
	private array $parking_floors = array();

	private function __construct() {}
	private function __clone() {}
	public function __wakeup() {}

	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new ParkingLot();
		}

		return self::$instance;
	}

	/**
	 * This function will issue ticket to the given vehicle.
	 *
	 * @param VehicleType $vehicle Vehicle type.
	 * @throws ParkingFullException Exception if parking is full.
	 * @return ParkingTicket Ticket for vehicle.
	 */
	public function issue_new_parking_ticket( $vehicle ) {
		if ( $this->is_full( $vehicle->get_type() ) ) {
			throw new ParkingFullException( ' Parking lot is full for vehicle type: ' . $vehicle->get_type() );
		}

		$ticket = new ParkingTicket();
		$vehicle->assign_ticket( $ticket );
		$ticket->save_in_db();
		$this->increment_spot_count( $vehicle->get_type() );
		$this->parking_tickets[ $ticket->get_ticket_number() ] = $ticket;
		return $ticket;
	}

	/**
	 * Function to check if parking lot is full for the vehicle type.
	 *
	 * @param VehicleType $vehicle_type Type of vehicle.
	 * @return bool If the parking is full or not.
	 */
	public function is_full( $vehicle_type ) {
		return false;
	}

	/**
	 * Function to increment the spot count for vehicle type.
	 *
	 * @param VehicleType $vehicle_type Holds the type of vehicle.
	 */
	private function increment_spot_count( $vehicle_type ) {
		// Incrementing the spot count of parking lot.
	}

	/**
	 * Function to add the parking floor on the parking lot.
	 *
	 * @return void
	 */
	private function add_parking_floor() {
		// Adding parking floor on the parking lot.
	}
}
