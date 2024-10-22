<?php
/**
 * Class representing a parking attendant.
 */
class ParkingAttendant {
    /**
     * Process a parking ticket.
     *
     * @param string $ticketNumber
     */
    public function processTicket(string $ticketNumber): void {
        echo "Processing ticket: " . $ticketNumber . "\n";
    }
}
