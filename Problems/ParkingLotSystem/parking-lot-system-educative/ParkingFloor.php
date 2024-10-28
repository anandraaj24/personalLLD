<?php

require_once 'Enums.php'; // Assuming constants.php contains the necessary enums
require_once 'ParkingDisplayBoard.php'; // Assuming parking_display_board.php contains the ParkingDisplayBoard class

class ParkingFloor {
    private $name;
    private $handicappedSpots = [];
    private $compactSpots = [];
    private $largeSpots = [];
    private $motorbikeSpots = [];
    private $electricSpots = [];
    private $infoPortals = [];
    private $freeHandicappedSpotCount = ['free_spot' => 0];
    private $freeCompactSpotCount = ['free_spot' => 0];
    private $freeLargeSpotCount = ['free_spot' => 0];
    private $freeMotorbikeSpotCount = ['free_spot' => 0];
    private $freeElectricSpotCount = ['free_spot' => 0];
    private $displayBoard;

    public function __construct($name) {
        $this->name = $name;
        $this->displayBoard = new ParkingDisplayBoard();
    }

    public function addParkingSpot(ParkingSpot $spot) {
        switch ($spot->getType()) {
            case ParkingSpotType::HANDICAPPED:
                $this->handicappedSpots[$spot->getNumber()] = $spot;
                break;
            case ParkingSpotType::COMPACT:
                $this->compactSpots[$spot->getNumber()] = $spot;
                break;
            case ParkingSpotType::LARGE:
                $this->largeSpots[$spot->getNumber()] = $spot;
                break;
            case ParkingSpotType::MOTORBIKE:
                $this->motorbikeSpots[$spot->getNumber()] = $spot;
                break;
            case ParkingSpotType::ELECTRIC:
                $this->electricSpots[$spot->getNumber()] = $spot;
                break;
            default:
                throw new Exception('Wrong parking spot type');
        }
    }

    public function assignVehicleToSpot($vehicle, ParkingSpot $spot) {
        $spot->assignVehicle($vehicle);
        switch ($spot->getType()) {
            case ParkingSpotType::HANDICAPPED:
                $this->updateDisplayBoardForHandicapped($spot);
                break;
            case ParkingSpotType::COMPACT:
                $this->updateDisplayBoardForCompact($spot);
                break;
            case ParkingSpotType::LARGE:
                $this->updateDisplayBoardForLarge($spot);
                break;
            case ParkingSpotType::MOTORBIKE:
                $this->updateDisplayBoardForMotorbike($spot);
                break;
            case ParkingSpotType::ELECTRIC:
                $this->updateDisplayBoardForElectric($spot);
                break;
            default:
                throw new Exception('Wrong parking spot type!');
        }
    }

    private function updateDisplayBoardForHandicapped(ParkingSpot $spot) {
        if ($this->displayBoard->getHandicappedFreeSpot()->getNumber() == $spot->getNumber()) {
            foreach ($this->handicappedSpots as $key => $spot) {
                if ($spot->isFree()) {
                    $this->displayBoard->setHandicappedFreeSpot($spot);
                    break; // Exit the loop after assigning
                }
            }
            $this->displayBoard->showEmptySpotNumber();
        }
    }

    private function updateDisplayBoardForCompact(ParkingSpot $spot) {
        if ($this->displayBoard->getCompactFreeSpot()->getNumber() == $spot->getNumber()) {
            foreach ($this->compactSpots as $key => $spot) {
                if ($spot->isFree()) {
                    $this->displayBoard->setCompactFreeSpot($spot);
                    break; // Exit the loop after assigning
                }
            }
            $this->displayBoard->showEmptySpotNumber();
        }
    }

    public function freeSpot(ParkingSpot $spot) {
        $spot->removeVehicle();
        switch ($spot->getType()) {
            case ParkingSpotType::HANDICAPPED:
                $this->freeHandicappedSpotCount['free_spot']++;
                break;
            case ParkingSpotType::COMPACT:
                $this->freeCompactSpotCount['free_spot']++;
                break;
            case ParkingSpotType::LARGE:
                $this->freeLargeSpotCount['free_spot']++;
                break;
            case ParkingSpotType::MOTORBIKE:
                $this->freeMotorbikeSpotCount['free_spot']++;
                break;
            case ParkingSpotType::ELECTRIC:
                $this->freeElectricSpotCount['free_spot']++;
                break;
            default:
                throw new Exception('Wrong parking spot type!');
        }
    }
}

?>
