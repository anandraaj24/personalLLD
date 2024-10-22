<?php
namespace Strategies;

use Models\ParkingTicket;

/**
 * Payment strategy for credit card payments.
 */
class CreditCardPaymentStrategy implements PaymentStrategy {
    public function pay($ticket) {
        echo "Processing credit card payment for ticket: " . $ticket->get_ticket_number() . PHP_EOL;
        $ticket->update_status("Paid");
    }
}
