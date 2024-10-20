<?php

namespace NgrokService;

/**
 * LoggingObserver - Observer implementation for logging tunnel events.
 */
class LoggingObserver implements TunnelObserver {
	/**
	 * Updates the observer with a logging event.
	 *
	 * @param Tunnel $tunnel The tunnel that triggered the event.
	 * @param string $event The event description.
	 */
	public function update( Tunnel $tunnel, string $event ): void {
		echo "Logging event: $event on tunnel " . $tunnel->get_type() . PHP_EOL;
	}
}
