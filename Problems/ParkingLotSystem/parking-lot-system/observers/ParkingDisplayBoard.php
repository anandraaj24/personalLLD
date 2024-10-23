<?php
namespace Observers;

/**
 * Class for updating and displaying parking status.
 */
class ParkingDisplayBoard implements Observer {
	private $free_spots = array();

	public function update( $message ) {
		echo 'Display Board Updated: ' . $message . PHP_EOL;
		$this->show_empty_spot_number();
	}

	public function show_empty_spot_number() {
		foreach ( $this->free_spots as $type => $spot ) {
			echo $spot->is_free() ? "Free $type Spot: " . $spot->get_number() . PHP_EOL : "$type full" . PHP_EOL;
		}
	}

	public function add_free_spot( $spot ) {
		$this->free_spots[ $spot->get_type() ] = $spot;
	}
}
