<?php

namespace NgrokService;

/**
 * TunnelObserver - Interface for observers that react to tunnel events.
 */
interface TunnelObserver {
	/**
	 * Updates the observer with a tunnel event.
	 *
	 * @param Tunnel $tunnel The tunnel that triggered the event.
	 * @param string $event The event description.
	 */
	public function update( Tunnel $tunnel, string $event ): void;
}
