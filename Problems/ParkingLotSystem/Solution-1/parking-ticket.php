<?php
/**
 * Class representing a parking ticket.
 */
class ParkingTicket {
    private static int $ticketCounter = 0;
    private string $ticketNumber;
    private ParkingTicketStatus $status;

    public function __construct() {
        self::$ticketCounter++;
        $this->ticketNumber = 'T' . self::$ticketCounter;
        $this->status = ParkingTicketStatus::ACTIVE;
    }

    /**
     * Save the ticket in the database (stub method).
     */
    public function saveInDB(): void {
        // Logic to save the ticket in the database (omitted for simplicity)
    }

    /**
     * Get the ticket number.
     *
     * @return string
     */
    public function getTicketNumber(): string {
        return $this->ticketNumber;
    }

    /**
     * Update the status of the ticket.
     *
     * @param ParkingTicketStatus $status
     */
    public function updateStatus(ParkingTicketStatus $status): void {
        $this->status = $status;
    }
}
