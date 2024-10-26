<?php
/**
 * File for ParkingLot Model.
 *
 * @package ParkingLotSystem
 */

/**
 * Main class for ParkingLot
 */
class ParkingLot {
	/**
	 * Holds the instance of ParkingLot (Singleton).
	 *
	 * @var ParkingLot Holds the instance of ParkingLot.
	 */
	private static ?ParkingLot $instance = null;

	/**
	 * Holds all the parking floor.
	 *
	 * @var array Array of parking floors in parking lot.
	 */
	private array $parking_floor = array();

	/**
	 * Holds all the tickets of parking lot.
	 *
	 * @var ParkingTicket[] Array of parking tickets.
	 */
	private array $parking_tickets = array();

	/**
	 * Private constructor to prevent the direct instantiation of the class.
	 */
	private function __construct() {}

	/**
	 * Private clone method to prevent cloning of the instance.
	 */
	private function __clone() {}

	/**
	 * Private wakeup method to prevent unserialization of the instance.
	 */
	private function __wakeup() {}

	/**
	 * Function to issue new parking ticket for vehicle.
	 *
	 * @param Vehicle $vehicle Holds the vehicle instance.
	 * @throws ParkingFullException Exception if parking is full.
	 * @return Ticket Ticket issued.
	 */
	public function issue_new_parking_ticket( Vehicle $vehicle ): Ticket {
		if ( $this->is_full( $vehicle->get_type() ) ) {
			throw new ParkingFullException( 'Parking is full for vehicle type: ' . $vehicle->get_type() );
		}

		$ticket = new ParkingTicket();
		$vehicle->assign_ticket( $ticket );
		$ticket->save_in_db();

		$this->increment_spot_count( $vehicle->get_type() );
		$this->parking_tickets[ $ticket->get_ticket_number() ] = $ticket;
		return $ticket;
	}

	/**
	 * Function to check if parking is full.
	 *
	 * @param VehicleType $vehicle_type Holds the type of vehicle.
	 * @return bool If parking is full or not.
	 */
	public function is_full( VehicleType $vehicle_type ): bool {
		return false;
	}

	/**
	 * Function to increment the parking spot count for vehicle.
	 *
	 * @param VehicleType $vehicle_type Holds the type of vehicle.
	 */
	private function increment_spot_count( VehicleType $vehicle_type ): void {
		// Incrementing the spot count.
	}

	/**
	 * Function to increment the parking floor for vehicle type.
	 *
	 * @param VehicleType $vehicle_type Holds the type of variable.
	 */
	private function add_parking_floor( VehicleType $vehicle_type ): void {
		$this->parking_floors[ $vehicle_type ] += 1;
	}
}
