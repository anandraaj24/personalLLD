<?php

namespace NgrokService;

/**
 * TunnelDecorator - Abstract class for decorating tunnel functionality.
 */
abstract class TunnelDecorator implements Tunnel {
	/**
	 * This is the original tunnel that the decorator extends.
	 *
	 * @var Tunnel The tunnel being decorated.
	 */
	protected Tunnel $decorated_tunnel;

	/**
	 * Constructor for TunnelDecorator.
	 *
	 * @param Tunnel $decorated_tunnel The tunnel to decorate.
	 */
	public function __construct( Tunnel $decorated_tunnel ) {
		$this->decorated_tunnel = $decorated_tunnel;
	}

	/**
	 * Starts the decorated tunnel.
	 */
	public function start(): void {
		$this->decorated_tunnel->start();
	}

	/**
	 * Returns the type of the decorated tunnel.
	 *
	 * @return string The type of the tunnel.
	 */
	public function get_type(): string {
		return $this->decorated_tunnel->get_type();
	}
}
