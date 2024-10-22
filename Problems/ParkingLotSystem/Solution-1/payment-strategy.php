<?php
/**
 * Interface representing a payment strategy.
 */
interface PaymentStrategy {
    public function pay(ParkingTicket $ticket): void;
}
