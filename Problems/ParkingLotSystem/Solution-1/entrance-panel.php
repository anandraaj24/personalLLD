<?php
/**
 * Class representing an entrance panel for the parking lot.
 */
class EntrancePanel {
    private string $id;

    public function __construct(string $id) {
        $this->id = $id;
    }

    /**
     * Get the ID of the entrance panel.
     *
     * @return string
     */
    public function getId(): string {
        return $this->id;
    }
}
