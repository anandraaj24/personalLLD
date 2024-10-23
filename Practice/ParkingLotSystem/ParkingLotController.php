<?php
/**
 * File for ParkingLotController.
 *
 * @package ParkingLotSystem
 */

namespace Controllers;

use Models\Car;
use Models\Truck;
use Models\ParkingLot;
use Models\ParkingAttendant;
use Factories\CarFactory;
use Factories\TruckFactory;
use Strategies\CreditCardPaymentStrategy;
use Strategies\CashPaymentStrategy;
use Commands\ProcessTicketCommand;
use Exceptions\ParkingFullException;

/**
 * Class for main parking lot controller.
 *
 * @package ParkingLotSystem
 */
class ParkingLotController {

	/**
	 * To store the instance of ParkingLot singleton class.
	 *
	 * @var ParkingLot Stores the singleton instance of parking lot.
	 */
	private $parking_lot;

	/**
	 * Stores the object of parking attendant.
	 *
	 * @var ParkingAttendant Store the object of parking attendant.
	 */
	private $parking_attendant;

	/**
	 * Constructor to initialize the parking lot controller.
	 *
	 * This constructor retrieves the ParkingLot single instance
	 * and ParkingAttendant instance and store in member variables.
	 */
	public function __construct() {
		$this->parking_lot       = ParkingLot::get_instance();
		$this->parking_attendant = new ParkingAttendant();
	}

	/**
	 * Function for issuing ticket to car.
	 *
	 * @return void
	 */
	public function issue_ticket_for_car() {
		$car_factory = new CarFactory();
		$car         = $car_factory->create_vehicle();

		try {
			$ticket = $this->parking_lot->get_new_parking_ticket( $car );
			echo 'Ticket issued for car: ' . $ticket->get_ticket_number() . PHP_EOL;

			$this->process_ticket( $ticket );
		} catch ( ParkingFullException $e ) {
			return $e->getMessage();
		}

		return 'Car ticket processed successfully';
	}

	/**
	 * Function to process the parking ticket.
	 *
	 * @param ParkingTicket $ticket The issued ticket.
	 * @return void
	 */
	private function process_ticket( $ticket ) {
		$process_command = new ProcessTicketCommand( $this->parking_attendant, $ticket->get_ticket_number() );
		$process_command->execute();

		$payment_strategy = new CashPaymentStrategy();
		$payment_strategy->pay( $ticket );
	}
}
