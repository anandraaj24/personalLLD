<?php

namespace NgrokService;

/**
 * HttpTunnel - Concrete implementation of the Tunnel interface for HTTP tunnels.
 */
class HttpTunnel implements Tunnel {
	/**
	 * This specifies where the HTTP tunnel will redirect traffic.
	 *
	 * @var string The local address for the HTTP tunnel.
	 */
	private string $local_address;

	/**
	 * Constructor for HttpTunnel.
	 *
	 * @param string $local_address The local address for the tunnel.
	 */
	public function __construct( string $local_address ) {
		$this->local_address = $local_address;
	}

	/**
	 * Starts the HTTP tunnel.
	 */
	public function start(): void {
		echo 'Starting HTTP Tunnel on ' . $this->local_address . PHP_EOL;
	}

	/**
	 * Returns the type of the tunnel.
	 *
	 * @return string The type of the tunnel (HTTP).
	 */
	public function get_type(): string {
		return 'HTTP';
	}
}
