<?php
/**
 * Class representing an exit panel for the parking lot.
 */
class ExitPanel {
    private string $id;

    public function __construct(string $id) {
        $this->id = $id;
    }

    /**
     * Get the ID of the exit panel.
     *
     * @return string
     */
    public function getId(): string {
        return $this->id;
    }
}
