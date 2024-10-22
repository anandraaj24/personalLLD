<?php
/**
 * Class representing the credit card payment strategy.
 */
class CreditCardPaymentStrategy implements PaymentStrategy {
    public function pay(ParkingTicket $ticket): void {
        echo "Paid the parking ticket using credit card.\n";
        $ticket->updateStatus(ParkingTicketStatus::PAID);
    }
}
