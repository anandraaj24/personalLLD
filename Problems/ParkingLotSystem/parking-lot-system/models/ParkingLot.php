<?php
namespace Models;

use Exceptions\ParkingFullException;
use Enums\VehicleType;

/**
 * Singleton class for managing the parking lot.
 */
class ParkingLot {
    private static $instance;
    private $parking_floors = [];
    private $active_tickets = [];

    private function __construct() {
        // Initialize parking lot details from a database or configuration
    }

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new ParkingLot();
        }
        return self::$instance;
    }

    public function get_new_parking_ticket($vehicle) {
        if ($this->is_full($vehicle->get_type())) {
            throw new ParkingFullException("Parking full for vehicle type: " . $vehicle->get_type());
        }

        $ticket = new ParkingTicket();
        $vehicle->assign_ticket($ticket);
        $ticket->save_in_db();
        $this->increment_spot_count($vehicle->get_type());
        $this->active_tickets[$ticket->get_ticket_number()] = $ticket;
        return $ticket;
    }

    public function is_full($vehicle_type) {
        // Implement logic to check if parking is full based on vehicle type
        return false; // Placeholder implementation
    }

    private function increment_spot_count($vehicle_type) {
        // Increment the spot count for the vehicle type
    }

    public function add_parking_floor($floor) {
        $this->parking_floors[$floor->get_name()] = $floor;
    }
}
