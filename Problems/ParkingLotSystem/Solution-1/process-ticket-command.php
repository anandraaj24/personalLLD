<?php
/**
 * Class representing the process ticket command.
 */
class ProcessTicketCommand implements Command {
    private ParkingAttendant $attendant;
    private string $ticketNumber;

    public function __construct(ParkingAttendant $attendant, string $ticketNumber) {
        $this->attendant = $attendant;
        $this->ticketNumber = $ticketNumber;
    }

    public function execute(): void {
        $this->attendant->processTicket($this->ticketNumber);
    }
}
