<?php
namespace Controllers;

use Models\ParkingLot;
use Models\Car;
use Models\Truck;
use Factories\CarFactory;
use Factories\TruckFactory;
use Models\ParkingAttendant;
use Commands\ProcessTicketCommand;
use Strategies\CashPaymentStrategy;
use Strategies\CreditCardPaymentStrategy;
use Exceptions\ParkingFullException;

/**
 * Controller for managing parking lot operations.
 */
class ParkingLotController {
    private $parking_lot;
    private $attendant;

    public function __construct() {
        $this->parking_lot = ParkingLot::get_instance();
        $this->attendant = new ParkingAttendant();
    }

    /**
     * Issues a parking ticket for a car.
     *
     * @return string
     */
    public function issue_ticket_for_car() {
        $car_factory = new CarFactory();
        $car = $car_factory->create_vehicle();

        try {
            $ticket = $this->parking_lot->get_new_parking_ticket($car);
            echo "Parking ticket issued for car: " . $ticket->get_ticket_number() . PHP_EOL;

            // Process the ticket
            $this->process_ticket($ticket);
        } catch (ParkingFullException $e) {
            return $e->getMessage();
        }

        return "Car ticket processed successfully.";
    }

    /**
     * Issues a parking ticket for a truck.
     *
     * @return string
     */
    public function issue_ticket_for_truck() {
        $truck_factory = new TruckFactory();
        $truck = $truck_factory->create_vehicle();

        try {
            $ticket = $this->parking_lot->get_new_parking_ticket($truck);
            echo "Parking ticket issued for truck: " . $ticket->get_ticket_number() . PHP_EOL;

            // Process the ticket
            $this->process_ticket($ticket);
        } catch (ParkingFullException $e) {
            return $e->getMessage();
        }

        return "Truck ticket processed successfully.";
    }

    /**
     * Processes a parking ticket.
     *
     * @param ParkingTicket $ticket
     */
    private function process_ticket($ticket) {
        // Use command pattern to process the ticket
        $process_command = new ProcessTicketCommand($this->attendant, $ticket->get_ticket_number());
        $process_command->execute();

        // Process payment
        $payment_strategy = new CashPaymentStrategy(); // or use CreditCardPaymentStrategy
        $payment_strategy->pay($ticket);
    }
}
