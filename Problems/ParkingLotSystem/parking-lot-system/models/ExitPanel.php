<?php
namespace Models;

/**
 * Class representing an exit panel.
 */
class ExitPanel {
	private $id;

	public function __construct( $id ) {
		$this->id = $id;
	}

	public function get_id() {
		return $this->id;
	}
}
