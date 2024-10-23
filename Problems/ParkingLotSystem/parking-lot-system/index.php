<?php

require_once 'vendor/autoload.php'; // Include Composer's autoloader

use Controllers\ParkingLotController;

// Instantiate the controller
$controller = new ParkingLotController();

// Issue a ticket for a car
echo $controller->issue_ticket_for_car() . PHP_EOL;

// Issue a ticket for a truck
echo $controller->issue_ticket_for_truck() . PHP_EOL;
