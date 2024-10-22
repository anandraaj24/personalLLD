<?php
/**
 * Class representing a parking floor in the parking lot.
 */
class ParkingFloor {
    private string $name;
    private int $capacity;
    private array $spots = [];

    public function __construct(string $name, int $capacity) {
        $this->name = $name;
        $this->capacity = $capacity;
    }

    /**
     * Add a parking spot to the floor.
     *
     * @param ParkingSpot $spot
     */
    public function addSpot(ParkingSpot $spot): void {
        if (count($this->spots) < $this->capacity) {
            $this->spots[] = $spot;
        }
    }

    /**
     * Get the available spots on the floor.
     *
     * @return ParkingSpot[]
     */
    public function getAvailableSpots(): array {
        return array_filter($this->spots, fn($spot) => $spot->isFree());
    }

    /**
     * Get the name of the parking floor.
     *
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }
}
