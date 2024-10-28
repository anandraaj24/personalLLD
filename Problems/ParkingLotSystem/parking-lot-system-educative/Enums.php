<?php

class VehicleType {
    const CAR = 1;
    const TRUCK = 2;
    const ELECTRIC = 3;
    const VAN = 4;
    const MOTORBIKE = 5;
}

class ParkingSpotType {
    const HANDICAPPED = 1;
    const COMPACT = 2;
    const LARGE = 3;
    const MOTORBIKE = 4;
    const ELECTRIC = 5;
}

class AccountStatus {
    const ACTIVE = 1;
    const BLOCKED = 2;
    const BANNED = 3;
    const COMPROMISED = 4;
    const ARCHIVED = 5;
    const UNKNOWN = 6;
}

class ParkingTicketStatus {
    const ACTIVE = 1;
    const PAID = 2;
    const LOST = 3;
}

class Address {
    private $streetAddress;
    private $city;
    private $state;
    private $zipCode;
    private $country;

    public function __construct($street, $city, $state, $zipCode, $country) {
        $this->streetAddress = $street;
        $this->city = $city;
        $this->state = $state;
        $this->zipCode = $zipCode;
        $this->country = $country;
    }
}

class Person {
    private $name;
    private $address;
    private $email;
    private $phone;

    public function __construct($name, Address $address, $email, $phone) {
        $this->name = $name;
        $this->address = $address;
        $this->email = $email;
        $this->phone = $phone;
    }
}
?>
