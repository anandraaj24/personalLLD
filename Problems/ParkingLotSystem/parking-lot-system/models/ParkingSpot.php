<?php
namespace Models;

/**
 * Abstract class representing a parking spot.
 */
abstract class ParkingSpot {
    private $number;
    private $is_free = true;
    private $vehicle;
    private $type;

    public function __construct($type) {
        $this->type = $type;
    }

    public function assign_vehicle($vehicle) {
        if (!$this->is_free) return false;
        $this->vehicle = $vehicle;
        $this->is_free = false;
        return true;
    }

    public function remove_vehicle() {
        $this->vehicle = null;
        $this->is_free = true;
        return true;
    }

    public function is_free() {
        return $this->is_free;
    }

    public function get_number() {
        return $this->number;
    }

    public function get_type() {
        return $this->type;
    }
}
