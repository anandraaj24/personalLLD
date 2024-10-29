<?php

// Enums
enum ParkingSpotType {
    case Handicapped;
    case Compact;
    case Large;
    case Motorbike;
    case Electric;
}

enum VehicleType {
    case Car;
    case Truck;
    case Electric;
    case Van;
    case Motorbike;
}

enum ParkingTicketStatus {
    case Active;
    case Paid;
    case Lost;
}

enum AccountStatus {
    case Active;
    case Closed;
    case Canceled;
    case Blacklisted;
    case None;
}


class ParkingRate {
  private $hourNumber;
  private $rate;

  public function __construct($hourNumber, $rate) {
    $this->hourNumber = $hourNumber;
    $this->rate = $rate;
  }

  public function getHourNumber() {
    return $this->hourNumber;
  }

  public function getRate() {
    return $this->rate;
  }
}

class ParkingLot {
  private $id;
  private $address;
  private $parkingFloor;
  private $entrancePanel;
  private $parkingTicket;
  private $isFull;

  public function __construct($id, $address) {
    $this->id = $id;
    $this->address = $address;
  }

  public function addParkingFloor($parkingFloor) {
    $this->parkingFloor = $parkingFloor;
  }

  public function addEnterancePanel($entrancePanel) {
    $this->entrancePanel = $entrancePanel;
  }

  public function getNewParkingTicket($parkingTicket) {
    $this->parkingTicket = $parkingTicket;
  }

  public function isFull() {
    return $this->isFull;
  }
}

class EntrancePanel {
  private $id;

  public function __construct($id) {
    $this->id = $id;
  }

  public function printTicket() {
    // Implement print ticket logic
  }
}

class ExitPanel {
  private $id;

  public function __construct($id) {
    $this->id = $id;
  }

  public function scanTicket() {
    // Implement scan ticket logic
  }

  public function processPayment() {
    // Implement process payment logic
  }
}

class ParkingTicket {
  private $tocketNumber;
  private $issuedAt;
  private $payedAt;
  private $payedAmount;
  private $status;

  public function __construct($tocketNumber, $issuedAt, $payedAt, $payedAmount, $status) {
    $this->tocketNumber = $tocketNumber;
    $this->issuedAt = $issuedAt;
    $this->payedAt = $payedAt;
    $this->payedAmount = $payedAmount;
    $this->status = $status;
  }

  public function getTocketNumber() {
    return $this->tocketNumber;
  }

  public function getIssuedAt() {
    return $this->issuedAt;
  }

  public function getPayedAt() {
    return $this->payedAt;
  }

  public function getPayedAmount() {
    return $this->payedAmount;
  }

  public function getStatus() {
    return $this->status;
  }
}

class ParkingFloor {
  private $name;
  private $parkingSpot;

  public function __construct($name) {
    $this->name = $name;
  }

  public function addParkingSpot($parkingSpot) {
    $this->parkingSpot = $parkingSpot;
  }

  public function updateDisplayBoard() {
    // Implement update display board logic
  }

  public function assignVehicleToSlot() {
    // Implement assign vehicle to slot logic
  }

  public function freeSlot() {
    // Implement free slot logic
  }
}

class ParkingSpot {
  private $number;
  private $free;
  private $type;

  public function __construct($number, $free, $type) {
    $this->number = $number;
    $this->free = $free;
    $this->type = $type;
  }

  public function getNumber() {
    return $this->number;
  }

  public function isFree() {
    return $this->free;
  }

  public function getType() {
    return $this->type;
  }
}

class ParkingDisplayBoard {
  private $handicappedFreeSpot;
  private $compactFreeSpot;
  private $largeFreeSpot;
  private $motorbikeFreeSpot;
  private $electricFreeSpot;

  public function __construct($handicappedFreeSpot, $compactFreeSpot, $largeFreeSpot, $motorbikeFreeSpot, $electricFreeSpot) {
    $this->handicappedFreeSpot = $handicappedFreeSpot;
    $this->compactFreeSpot = $compactFreeSpot;
    $this->largeFreeSpot = $largeFreeSpot;
    $this->motorbikeFreeSpot = $motorbikeFreeSpot;
    $this->electricFreeSpot = $electricFreeSpot;
  }

  public function showEmptySpotNumber() {
    // Implement show empty spot number logic
  }
}

class ElectricPanel {
  private $payedForMinutes;
  private $chargingStartTime;

  public function __construct($payedForMinutes, $chargingStartTime) {
    $this->payedForMinutes = $payedForMinutes;
    $this->chargingStartTime = $chargingStartTime;
  }

  public function cancelCharging() {
    // Implement cancel charging logic
  }
}

class Payment {
  private $creationDate;
  private $amount;
  private $status;

  public function __construct($creationDate, $amount, $status) {
    $this->creationDate = $creationDate;
    $this->amount = $amount;
    $this->status = $status;
  }

  public function getCreationDate() {
    return $this->creationDate;
  }

  public function getAmount() {
    return $this->amount;
  }

  public function getStatus() {
    return $this->status;
  }

  public function initiateTransaction() {
    // Implement initiate transaction logic
  }
}

class CreditCardTransaction {
  private $nameOnCard;

  public function __construct($nameOnCard) {
    $this->nameOnCard = $nameOnCard;
  }

  public function getNameOnCard() {
    return $this->nameOnCard;
  }
}

class CashTransaction {
  private $cashTendered;

  public function __construct($cashTendered) {
    $this->cashTendered = $cashTendered;
  }

  public function getCashTendered() {
    return $this->cashTendered;
  }
}

class Vehicle {
  private $licenseNumber;

  public function __construct($licenseNumber) {
    $this->licenseNumber = $licenseNumber;
  }

  public function getLicenseNumber() {
    return $this->licenseNumber;
  }
}
?>