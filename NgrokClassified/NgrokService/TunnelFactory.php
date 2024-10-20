<?php

namespace NgrokService;

use InvalidArgumentException;

/**
 * TunnelFactory - Factory class for creating tunnel instances.
 */
class TunnelFactory {
	/**
	 * Creates a tunnel based on the provided type and local address.
	 *
	 * @param string $type The type of tunnel to create (HTTP or TCP).
	 * @param string $local_address The local address for the tunnel.
	 * @return Tunnel The created tunnel instance.
	 * @throws InvalidArgumentException If the tunnel type is unknown or the address is invalid.
	 */
	public static function create_tunnel( string $type, string $local_address ): Tunnel {
		self::validate_local_address( $local_address );

		return match ( strtoupper( $type ) ) {
			'HTTP' => new HttpTunnel( $local_address ),
			'TCP' => new TcpTunnel( $local_address ),
			default => throw new InvalidArgumentException( "Unknown tunnel type: $type" ),
		};
	}

	/**
	 * Validates the provided local address.
	 *
	 * @param string $local_address The local address to validate.
	 * @throws InvalidArgumentException If the address is invalid.
	 */
	private static function validate_local_address( string $local_address ): void {
		if ( ! filter_var( $local_address, FILTER_VALIDATE_URL ) ) {
			throw new InvalidArgumentException( "Invalid local address: $local_address" );
		}
	}
}
