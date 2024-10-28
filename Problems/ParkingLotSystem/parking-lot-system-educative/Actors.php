<?php

require_once 'Enums.php'; // Assuming constants.php contains the necessary enums

class Account {
    private $userName;
    private $password;
    private $person;
    private $status;

    public function __construct($userName, $password, Person $person, $status = AccountStatus::ACTIVE) {
        $this->userName = $userName;
        $this->password = $password;
        $this->person = $person;
        $this->status = $status;
    }

    public function resetPassword() {
        // Implementation for password reset
    }
}

class Admin extends Account {
    public function __construct($userName, $password, Person $person, $status = AccountStatus::ACTIVE) {
        parent::__construct($userName, $password, $person, $status);
    }

    public function addParkingFloor($floor) {
        // Implementation for adding a parking floor
    }

    public function addParkingSpot($floorName, $spot) {
        // Implementation for adding a parking spot
    }

    public function addParkingDisplayBoard($floorName, $displayBoard) {
        // Implementation for adding a parking display board
    }

    public function addCustomerInfoPanel($floorName, $infoPanel) {
        // Implementation for adding a customer info panel
    }

    public function addEntrancePanel($entrancePanel) {
        // Implementation for adding an entrance panel
    }

    public function addExitPanel($exitPanel) {
        // Implementation for adding an exit panel
    }
}

class ParkingAttendant extends Account {
    public function __construct($userName, $password, Person $person, $status = AccountStatus::ACTIVE) {
        parent::__construct($userName, $password, $person, $status);
    }

    public function processTicket($ticketNumber) {
        // Implementation for processing a ticket
    }
}

?>
