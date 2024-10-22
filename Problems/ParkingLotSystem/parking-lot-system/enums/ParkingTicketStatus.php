<?php
namespace Enums;

/**
 * Enum for parking ticket statuses.
 */
class ParkingTicketStatus {
    const ACTIVE = "Ticket Active";
    const PAID = "Ticket Paid";
    const LOST = "Ticket Lost";

    public static function is_paid($status) {
        return $status === self::PAID;
    }
}
