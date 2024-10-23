<?php
namespace Models;

/**
 * Abstract class representing a vehicle.
 */
abstract class Vehicle {
	private $type;
	private $ticket;

	public function __construct( $type ) {
		$this->type = $type;
	}

	public function get_type() {
		return $this->type;
	}

	public function assign_ticket( $ticket ) {
		$this->ticket = $ticket;
	}
}
