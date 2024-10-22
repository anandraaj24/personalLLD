<?php
/**
 * Class to represent an address for the parking lot.
 */
class Address {
    private string $street;
    private string $city;
    private string $zipCode;
    private string $country;

    public function __construct(string $street, string $city, string $zipCode, string $country) {
        $this->street = $street;
        $this->city = $city;
        $this->zipCode = $zipCode;
        $this->country = $country;
    }

    /**
     * Get the full address.
     *
     * @return string
     */
    public function getFullAddress(): string {
        return "{$this->street}, {$this->city}, {$this->zipCode}, {$this->country}";
    }
}
