<?php

namespace NgrokService;

/**
 * LoggingTunnelDecorator - Decorator class that adds logging functionality to a tunnel.
 */
class LoggingTunnelDecorator extends TunnelDecorator {
	/**
	 * Starts the decorated tunnel and logs the event.
	 */
	public function start(): void {
		echo "Logging enabled for tunnel: " . $this->decorated_tunnel->get_type() . PHP_EOL;
		parent::start();
	}
}
