<?php
/**
 * Class representing a parking display board.
 */
class ParkingDisplayBoard implements Observer {
    private string $id;
    private ?ParkingSpot $handicappedFreeSpot = null;
    private ?ParkingSpot $compactFreeSpot = null;
    private ?ParkingSpot $largeFreeSpot = null;

    public function __construct(string $id) {
        $this->id = $id;
    }

    /**
     * Update the display board with the latest status.
     *
     * @param ParkingSpot $spot
     */
    public function update(ParkingSpot $spot): void {
        if ($spot->getType() === ParkingSpotType::HANDICAPPED) {
            $this->handicappedFreeSpot = $spot;
        } elseif ($spot->getType() === ParkingSpotType::COMPACT) {
            $this->compactFreeSpot = $spot;
        } elseif ($spot->getType() === ParkingSpotType::LARGE) {
            $this->largeFreeSpot = $spot;
        }
        echo "Updated display board: Handicapped Free: " . ($this->handicappedFreeSpot ? 'Yes' : 'No') . "\n";
    }
}
