<?php

class ParkingDisplayBoard {
    private $id;
    private $handicappedFreeSpot;
    private $compactFreeSpot;
    private $largeFreeSpot;
    private $motorbikeFreeSpot;
    private $electricFreeSpot;

    public function __construct($id) {
        $this->id = $id;
        $this->handicappedFreeSpot = null;
        $this->compactFreeSpot = null;
        $this->largeFreeSpot = null;
        $this->motorbikeFreeSpot = null;
        $this->electricFreeSpot = null;
    }

    public function showEmptySpotNumber() {
        $message = "";

        if ($this->handicappedFreeSpot && $this->handicappedFreeSpot->isFree()) {
            $message .= "Free Handicapped: " . $this->handicappedFreeSpot->getNumber();
        } else {
            $message .= "Handicapped is full";
        }
        $message .= "\n";

        if ($this->compactFreeSpot && $this->compactFreeSpot->isFree()) {
            $message .= "Free Compact: " . $this->compactFreeSpot->getNumber();
        } else {
            $message .= "Compact is full";
        }
        $message .= "\n";

        if ($this->largeFreeSpot && $this->largeFreeSpot->isFree()) {
            $message .= "Free Large: " . $this->largeFreeSpot->getNumber();
        } else {
            $message .= "Large is full";
        }
        $message .= "\n";

        if ($this->motorbikeFreeSpot && $this->motorbikeFreeSpot->isFree()) {
            $message .= "Free Motorbike: " . $this->motorbikeFreeSpot->getNumber();
        } else {
            $message .= "Motorbike is full";
        }
        $message .= "\n";

        if ($this->electricFreeSpot && $this->electricFreeSpot->isFree()) {
            $message .= "Free Electric: " . $this->electricFreeSpot->getNumber();
        } else {
            $message .= "Electric is full";
        }

        echo nl2br($message); // Use nl2br to convert newlines to HTML line breaks
    }

    // Getters and setters for free spots
    public function setHandicappedFreeSpot($spot) {
        $this->handicappedFreeSpot = $spot;
    }

    public function setCompactFreeSpot($spot) {
        $this->compactFreeSpot = $spot;
    }

    public function setLargeFreeSpot($spot) {
        $this->largeFreeSpot = $spot;
    }

    public function setMotorbikeFreeSpot($spot) {
        $this->motorbikeFreeSpot = $spot;
    }

    public function setElectricFreeSpot($spot) {
        $this->electricFreeSpot = $spot;
    }

    public function getHandicappedFreeSpot() {
        return $this->handicappedFreeSpot;
    }

    public function getCompactFreeSpot() {
        return $this->compactFreeSpot;
    }

    public function getLargeFreeSpot() {
        return $this->largeFreeSpot;
    }

    public function getMotorbikeFreeSpot() {
        return $this->motorbikeFreeSpot;
    }

    public function getElectricFreeSpot() {
        return $this->electricFreeSpot;
    }
}

?>
