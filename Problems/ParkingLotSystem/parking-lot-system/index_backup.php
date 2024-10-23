<?php
require_once 'enums/VehicleType.php';
require_once 'enums/ParkingSpotType.php';
require_once 'enums/AccountStatus.php';
require_once 'enums/ParkingTicketStatus.php';
require_once 'exceptions/ParkingFullException.php';
require_once 'models/ParkingLot.php';
require_once 'models/ParkingFloor.php';
require_once 'models/ParkingTicket.php';
require_once 'models/Car.php';
require_once 'models/Truck.php';
require_once 'models/ParkingAttendant.php';
require_once 'factories/CarFactory.php';
require_once 'factories/TruckFactory.php';
require_once 'commands/ProcessTicketCommand.php';
require_once 'strategies/CashPaymentStrategy.php';
require_once 'strategies/CreditCardPaymentStrategy.php';

// Main script for testing the parking lot system
use Models\ParkingLot;
use Factories\CarFactory;
use Models\Car;
use Commands\ProcessTicketCommand;
use Strategies\CashPaymentStrategy;
use Models\ParkingAttendant;
use Exceptions\ParkingFullException;

try {
	// Get the singleton instance of ParkingLot
	$parking_lot = ParkingLot::get_instance();

	// Create vehicles using the factory
	$car_factory = new CarFactory();
	$car         = $car_factory->create_vehicle();

	// Create a parking ticket for the vehicle
	$ticket = $parking_lot->get_new_parking_ticket( $car );
	echo 'Parking ticket issued: ' . $ticket->get_ticket_number() . PHP_EOL;

	// Process the ticket using the command pattern
	$attendant              = new ParkingAttendant();
	$process_ticket_command = new ProcessTicketCommand( $attendant, $ticket->get_ticket_number() );
	$process_ticket_command->execute();

	// Pay for the ticket using the cash payment strategy
	$payment_strategy = new CashPaymentStrategy();
	$payment_strategy->pay( $ticket );

} catch ( ParkingFullException $e ) {
	echo $e->getMessage();
}
