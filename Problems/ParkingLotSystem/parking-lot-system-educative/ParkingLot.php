<?php

require_once 'Enums.php'; // Assuming this contains necessary enums and classes
require_once 'ParkingTicket.php'; // Assuming this contains the ParkingTicket class

class ParkingLot {
    private static $instance = null;

    private $name;
    private $address;
    private $parkingRate;

    private $compactSpotCount = 0;
    private $largeSpotCount = 0;
    private $motorbikeSpotCount = 0;
    private $electricSpotCount = 0;
    private $maxCompactCount = 0;
    private $maxLargeCount = 0;
    private $maxMotorbikeCount = 0;
    private $maxElectricCount = 0;

    private $entrancePanels = [];
    private $exitPanels = [];
    private $parkingFloors = [];

    // All active parking tickets, identified by their ticket_number
    private $activeTickets = [];

    private $lock;

    private function __construct($name, $address) {
        $this->name = $name;
        $this->address = $address;
        $this->parkingRate = new ParkingRate(); // Assuming ParkingRate is a class

        // Initialize spot counts from database or defaults
        // Database initialization logic would go here
        $this->lock = new Mutex(); // Using Mutex for thread-safe locking
    }

    public static function getInstance($name = null, $address = null) {
        if (self::$instance == null) {
            self::$instance = new ParkingLot($name, $address);
        } else {
            if ($name !== null) {
                self::$instance->name = $name;
            }
            if ($address !== null) {
                self::$instance->address = $address;
            }
        }
        return self::$instance;
    }

    public function getNewParkingTicket($vehicle) {
        if ($this->isFull($vehicle->getType())) {
            throw new Exception('Parking full!');
        }

        // Synchronizing to allow multiple entrance panels to issue a new parking ticket
        $this->lock->acquire();
        $ticket = new ParkingTicket();
        $vehicle->assignTicket($ticket);
        $ticket->saveInDB(); // Assuming saveInDB method exists in ParkingTicket class

        // If the ticket is successfully saved in the database, increment the parking spot count
        $this->incrementSpotCount($vehicle->getType());
        $this->activeTickets[$ticket->getTicketNumber()] = $ticket;
        $this->lock->release();

        return $ticket;
    }

    public function isFull($type) {
        if ($type == VehicleType::TRUCK || $type == VehicleType::VAN) {
            return $this->largeSpotCount >= $this->maxLargeCount;
        }

        if ($type == VehicleType::MOTORBIKE) {
            return $this->motorbikeSpotCount >= $this->maxMotorbikeCount;
        }

        if ($type == VehicleType::CAR) {
            return ($this->compactSpotCount + $this->largeSpotCount) >= ($this->maxCompactCount + $this->maxLargeCount);
        }

        return ($this->compactSpotCount + $this->largeSpotCount + $this->electricSpotCount) >= 
               ($this->maxCompactCount + $this->maxLargeCount + $this->maxElectricCount);
    }

    public function incrementSpotCount($type) {
        if ($type == VehicleType::TRUCK || $type == VehicleType::VAN) {
            $this->largeSpotCount++;
        } elseif ($type == VehicleType::MOTORBIKE) {
            $this->motorbikeSpotCount++;
        } elseif ($type == VehicleType::CAR) {
            if ($this->compactSpotCount < $this->maxCompactCount) {
                $this->compactSpotCount++;
            } else {
                $this->largeSpotCount++;
            }
        } else { // Electric car
            if ($this->electricSpotCount < $this->maxElectricCount) {
                $this->electricSpotCount++;
            } elseif ($this->compactSpotCount < $this->maxCompactCount) {
                $this->compactSpotCount++;
            } else {
                $this->largeSpotCount++;
            }
        }
    }

    public function isFullParkingLot() {
        foreach ($this->parkingFloors as $floor) {
            if (!$floor->isFull()) {
                return false;
            }
        }
        return true;
    }

    public function addParkingFloor($floor) {
        // Store in database
        // Database insertion logic would go here
    }

    public function addEntrancePanel($entrancePanel) {
        // Store in database
        // Database insertion logic would go here
    }

    public function addExitPanel($exitPanel) {
        // Store in database
        // Database insertion logic would go here
    }
}

?>
