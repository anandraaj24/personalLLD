<?php
/**
 * Class representing the cash payment strategy.
 */
class CashPaymentStrategy implements PaymentStrategy {
    public function pay(ParkingTicket $ticket): void {
        echo "Paid the parking ticket using cash.\n";
        $ticket->updateStatus(ParkingTicketStatus::PAID);
    }
}
