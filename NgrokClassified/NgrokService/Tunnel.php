<?php

namespace NgrokService;

/**
 * Tunnel - Interface for tunnel implementations.
 *
 * This interface defines the methods that any tunnel type must implement.
 */
interface Tunnel {
	/**
	 * Starts the tunnel.
	 */
	public function start(): void;

	/**
	 * Returns the type of the tunnel.
	 *
	 * @return string The type of the tunnel.
	 */
	public function get_type(): string;
}
