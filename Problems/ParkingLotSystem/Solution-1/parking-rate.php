<?php
/**
 * Class representing the parking rate for different vehicle types.
 */
class ParkingRate {
    private float $carRate;
    private float $truckRate;
    private float $motorbikeRate;
    private float $electricRate;

    public function __construct(float $carRate, float $truckRate, float $motorbikeRate, float $electricRate) {
        $this->carRate = $carRate;
        $this->truckRate = $truckRate;
        $this->motorbikeRate = $motorbikeRate;
        $this->electricRate = $electricRate;
    }

    /**
     * Get the rate for a specific vehicle type.
     *
     * @param VehicleType $vehicleType
     * @return float
     */
    public function getRateForVehicleType(VehicleType $vehicleType): float {
        return match ($vehicleType) {
            VehicleType::CAR => $this->carRate,
            VehicleType::TRUCK => $this->truckRate,
            VehicleType::MOTORBIKE => $this->motorbikeRate,
            VehicleType::ELECTRIC => $this->electricRate,
        };
    }
}
