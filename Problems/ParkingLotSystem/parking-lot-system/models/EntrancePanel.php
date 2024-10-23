<?php
namespace Models;

/**
 * Class representing an entrance panel.
 */
class EntrancePanel {
	private $id;

	public function __construct( $id ) {
		$this->id = $id;
	}

	public function get_id() {
		return $this->id;
	}
}
