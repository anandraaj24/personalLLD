<?php

namespace NgrokService;

/**
 * MonitoringObserver - Observer implementation for monitoring tunnel events.
 */
class MonitoringObserver implements TunnelObserver {
	/**
	 * Updates the observer with a monitoring event.
	 *
	 * @param Tunnel $tunnel The tunnel that triggered the event.
	 * @param string $event The event description.
	 */
	public function update( Tunnel $tunnel, string $event ): void {
		echo "Monitoring event: $event on tunnel " . $tunnel->get_type() . PHP_EOL;
	}
}
