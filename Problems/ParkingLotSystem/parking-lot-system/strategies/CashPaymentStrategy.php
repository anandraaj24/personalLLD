<?php
namespace Strategies;

use Models\ParkingTicket;

/**
 * Payment strategy for cash payments.
 */
class CashPaymentStrategy implements PaymentStrategy {
	public function pay( $ticket ) {
		echo 'Processing cash payment for ticket: ' . $ticket->get_ticket_number() . PHP_EOL;
		$ticket->update_status( 'Paid' );
	}
}
