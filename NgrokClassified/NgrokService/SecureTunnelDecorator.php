<?php

namespace NgrokService;

/**
 * SecureTunnelDecorator - Decorator class that adds encryption functionality to a tunnel.
 */
class SecureTunnelDecorator extends TunnelDecorator {
	/**
	 * Starts the decorated tunnel and enables encryption.
	 */
	public function start(): void {
		echo "Encryption enabled for tunnel: " . $this->decorated_tunnel->get_type() . PHP_EOL;
		parent::start();
	}
}
