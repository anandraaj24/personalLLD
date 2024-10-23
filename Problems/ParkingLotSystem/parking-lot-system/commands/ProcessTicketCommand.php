<?php
namespace Commands;

use Models\ParkingAttendant;

/**
 * Command to process parking tickets.
 */
class ProcessTicketCommand implements Command {
	private $attendant;
	private $ticket_number;

	public function __construct( ParkingAttendant $attendant, $ticket_number ) {
		$this->attendant     = $attendant;
		$this->ticket_number = $ticket_number;
	}

	public function execute() {
		$this->attendant->process_ticket( $this->ticket_number );
	}
}
