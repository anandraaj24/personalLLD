<?php

namespace NgrokService;

/**
 * TunnelBuilder - Builder class for creating complex tunnel configurations.
 */
class TunnelBuilder {
	/**
	 * This is the tunnel that will be modified by the builder.
	 *
	 * @var Tunnel The base tunnel to configure.
	 */
	private Tunnel $tunnel;

	/**
	 * This determines whether the logging feature is applied to the tunnel.
	 *
	 * @var bool Indicates if logging is enabled.
	 */
	private bool $logging_enabled = false;

	/**
	 * This determines whether the encryption feature is applied to the tunnel.
	 *
	 * @var bool Indicates if encryption is enabled.
	 */
	private bool $encryption_enabled = false;

	/**
	 * Constructor for TunnelBuilder.
	 *
	 * @param string $type The type of tunnel to create.
	 * @param string $local_address The local address for the tunnel.
	 */
	public function __construct( string $type, string $local_address ) {
		$this->tunnel = TunnelFactory::create_tunnel( $type, $local_address );
	}

	/**
	 * Enables logging for the tunnel.
	 *
	 * @return $this The builder instance for chaining.
	 */
	public function enable_logging(): self {
		$this->logging_enabled = true;
		return $this;
	}

	/**
	 * Enables encryption for the tunnel.
	 *
	 * @return $this The builder instance for chaining.
	 */
	public function enable_encryption(): self {
		$this->encryption_enabled = true;
		return $this;
	}

	/**
	 * Builds and returns the configured tunnel.
	 *
	 * @return Tunnel The configured tunnel instance.
	 */
	public function build(): Tunnel {
		if ( $this->logging_enabled ) {
			$this->tunnel = new LoggingTunnelDecorator( $this->tunnel );
		}
		if ( $this->encryption_enabled ) {
			$this->tunnel = new SecureTunnelDecorator( $this->tunnel );
		}
		return $this->tunnel;
	}
}
