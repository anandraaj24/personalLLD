<?php
/**
 * Abstract class representing a parking spot.
 */
abstract class ParkingSpot {
    private string $number;
    private bool $free = true;
    private ?Vehicle $vehicle = null;
    private ParkingSpotType $type;

    public function __construct(ParkingSpotType $type) {
        $this->type = $type;
    }

    /**
     * Assign a vehicle to the parking spot.
     *
     * @param Vehicle $vehicle
     * @return bool
     */
    public function assignVehicle(Vehicle $vehicle): bool {
        if (!$this->isFree()) return false;
        $this->vehicle = $vehicle;
        $this->free = false;
        return true;
    }

    /**
     * Remove the vehicle from the parking spot.
     *
     * @return bool
     */
    public function removeVehicle(): bool {
        $this->vehicle = null;
        $this->free = true;
        return true;
    }

    /**
     * Check if the spot is free.
     *
     * @return bool
     */
    public function isFree(): bool {
        return $this->free;
    }

    /**
     * Get the parking spot number.
     *
     * @return string
     */
    public function getNumber(): string {
        return $this->number;
    }

    /**
     * Get the type of the parking spot.
     *
     * @return ParkingSpotType
     */
    public function getType(): ParkingSpotType {
        return $this->type;
    }
}
