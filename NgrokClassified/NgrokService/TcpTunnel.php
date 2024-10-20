<?php

namespace NgrokService;

/**
 * TcpTunnel - Concrete implementation of the Tunnel interface for TCP tunnels.
 */
class TcpTunnel implements Tunnel {
	/**
	 * This specifies where the TCP tunnel will redirect traffic.
	 *
	 * @var string The local address for the TCP tunnel.
	 */
	private string $local_address;

	/**
	 * Constructor for TcpTunnel.
	 *
	 * @param string $local_address The local address for the tunnel.
	 */
	public function __construct( string $local_address ) {
		$this->local_address = $local_address;
	}

	/**
	 * Starts the TCP tunnel.
	 */
	public function start(): void {
		echo 'Starting TCP Tunnel on ' . $this->local_address . PHP_EOL;
	}

	/**
	 * Returns the type of the tunnel.
	 *
	 * @return string The type of the tunnel (TCP).
	 */
	public function get_type(): string {
		return 'TCP';
	}
}
