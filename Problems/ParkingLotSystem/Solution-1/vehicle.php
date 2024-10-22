<?php
/**
 * Abstract class representing a vehicle.
 */
abstract class Vehicle {
    private VehicleType $type;
    private ?ParkingTicket $ticket = null;

    public function __construct(VehicleType $type) {
        $this->type = $type;
    }

    /**
     * Get the vehicle type.
     *
     * @return VehicleType
     */
    public function getType(): VehicleType {
        return $this->type;
    }

    /**
     * Assign a parking ticket to the vehicle.
     *
     * @param ParkingTicket $ticket
     */
    public function assignTicket(ParkingTicket $ticket): void {
        $this->ticket = $ticket;
    }

    /**
     * Get the parking ticket assigned to the vehicle.
     *
     * @return ParkingTicket|null
     */
    public function getTicket(): ?ParkingTicket {
        return $this->ticket;
    }
}
