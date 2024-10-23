<?php
namespace Models;

/**
 * Class representing a parking ticket.
 */
class ParkingTicket {
	private $ticket_number;
	private $status;

	public function __construct() {
		// Generate ticket number and set initial status
		$this->ticket_number = uniqid( 'ticket_' );
		$this->status        = 'Active';
	}

	public function get_ticket_number() {
		return $this->ticket_number;
	}

	public function update_status( $status ) {
		$this->status = $status;
	}

	public function save_in_db() {
		// Logic to save the ticket in a database
	}
}
