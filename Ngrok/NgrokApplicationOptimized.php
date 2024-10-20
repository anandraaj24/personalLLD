<?php

/**
 * NgrokService - Singleton class for managing NGROK tunnels.
 *
 * This class ensures that only one instance of the service exists throughout
 * the application, providing methods to add and start tunnels.
 */
class NgrokService {
	/**
	 * @var NgrokService|null The singleton instance of NgrokService.
	 */
	private static ?NgrokService $instance = null;

	/**
	 * @var Tunnel[] An array to hold tunnel instances.
	 */
	private array $tunnels = array();

	// Private constructor to prevent direct instantiation
	private function __construct() {}

	/**
	 * Retrieves the singleton instance of NgrokService.
	 *
	 * @return NgrokService The instance of NgrokService.
	 */
	public static function get_instance(): NgrokService {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	// Prevent cloning of the instance
	private function __clone() {}

	// Prevent unserializing of the instance
	private function __wakeup() {}

	/**
	 * Adds a tunnel to the service.
	 *
	 * @param Tunnel $tunnel The tunnel instance to add.
	 */
	public function add_tunnel( Tunnel $tunnel ): void {
		$this->tunnels[] = $tunnel;
		echo 'Tunnel added: ' . $tunnel->get_type() . PHP_EOL;
	}

	/**
	 * Starts all tunnels managed by the service.
	 */
	public function start_all_tunnels(): void {
		foreach ( $this->tunnels as $tunnel ) {
			$tunnel->start();
		}
	}
}

/**
 * TunnelFactory - Factory class for creating tunnel instances.
 *
 * This class provides methods to create different types of tunnels and
 * ensures valid local addresses.
 */
class TunnelFactory {
	/**
	 * Creates a tunnel based on the specified type and local address.
	 *
	 * @param string $type The type of the tunnel (HTTP or TCP).
	 * @param string $local_address The local address for the tunnel.
	 * @return Tunnel The created tunnel instance.
	 * @throws InvalidArgumentException if the tunnel type is unknown or if
	 *                                  the local address is invalid.
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
	 * Validates the given local address.
	 *
	 * @param string $local_address The local address to validate.
	 * @throws InvalidArgumentException if the local address is invalid.
	 */
	private static function validate_local_address( string $local_address ): void {
		if ( ! filter_var( $local_address, FILTER_VALIDATE_URL ) ) {
			throw new InvalidArgumentException( "Invalid local address: $local_address" );
		}
	}
}

/**
 * Tunnel - Interface for tunnel implementations.
 *
 * This interface defines the methods that must be implemented by all
 * tunnel types.
 */
interface Tunnel {
	public function start(): void; // Method to start the tunnel
	public function get_type(): string; // Method to get the tunnel type
}

/**
 * HttpTunnel - Concrete implementation of the Tunnel interface for HTTP tunnels.
 */
class HttpTunnel implements Tunnel {
	/**
	 * @var string The local address for the HTTP tunnel.
	 */
	private string $local_address;

	/**
	 * Constructor for the HttpTunnel class.
	 *
	 * @param string $local_address The local address for the HTTP tunnel.
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
	 * Gets the type of the tunnel.
	 *
	 * @return string The type of the tunnel.
	 */
	public function get_type(): string {
		return 'HTTP';
	}
}

/**
 * TcpTunnel - Concrete implementation of the Tunnel interface for TCP tunnels.
 */
class TcpTunnel implements Tunnel {
	/**
	 * @var string The local address for the TCP tunnel.
	 */
	private string $local_address;

	/**
	 * Constructor for the TcpTunnel class.
	 *
	 * @param string $local_address The local address for the TCP tunnel.
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
	 * Gets the type of the tunnel.
	 *
	 * @return string The type of the tunnel.
	 */
	public function get_type(): string {
		return 'TCP';
	}
}

/**
 * TunnelObserver - Interface for observers that react to tunnel events.
 */
interface TunnelObserver {
	public function update( Tunnel $tunnel, string $event ): void; // Method to update observer
}

/**
 * LoggingObserver - Observer implementation for logging tunnel events.
 */
class LoggingObserver implements TunnelObserver {
	/**
	 * Updates the observer with tunnel events.
	 *
	 * @param Tunnel $tunnel The tunnel that triggered the event.
	 * @param string $event The event description.
	 */
	public function update( Tunnel $tunnel, string $event ): void {
		echo "Logging event: $event on tunnel " . $tunnel->get_type() . PHP_EOL;
	}
}

/**
 * MonitoringObserver - Observer implementation for monitoring tunnel events.
 */
class MonitoringObserver implements TunnelObserver {
	/**
	 * Updates the observer with tunnel events.
	 *
	 * @param Tunnel $tunnel The tunnel that triggered the event.
	 * @param string $event The event description.
	 */
	public function update( Tunnel $tunnel, string $event ): void {
		echo "Monitoring event: $event on tunnel " . $tunnel->get_type() . PHP_EOL;
	}
}

/**
 * LoadBalancingStrategy - Interface for tunnel load balancing strategies.
 */
interface LoadBalancingStrategy {
	public function select_tunnel( array $tunnels ): Tunnel; // Method to select a tunnel
}

/**
 * RoundRobinStrategy - Load balancing strategy that selects tunnels in a round-robin manner.
 */
class RoundRobinStrategy implements LoadBalancingStrategy {
	/**
	 * @var int The current index for round-robin selection.
	 */
	private int $current_index = 0;

	/**
	 * Selects a tunnel using round-robin strategy.
	 *
	 * @param array $tunnels The array of tunnels to choose from.
	 * @return Tunnel The selected tunnel.
	 */
	public function select_tunnel( array $tunnels ): Tunnel {
		$tunnel              = $tunnels[ $this->current_index ];
		$this->current_index = ( $this->current_index + 1 ) % count( $tunnels );
		return $tunnel;
	}
}

/**
 * LeastConnectionsStrategy - Load balancing strategy that selects the tunnel with the least connections.
 */
class LeastConnectionsStrategy implements LoadBalancingStrategy {
	/**
	 * Selects a tunnel based on the least connections strategy.
	 *
	 * @param array $tunnels The array of tunnels to choose from.
	 * @return Tunnel The selected tunnel (simplified for this example).
	 */
	public function select_tunnel( array $tunnels ): Tunnel {
		return $tunnels[0]; // Simplified for this example
	}
}

/**
 * TunnelDecorator - Abstract class for decorating tunnel functionality.
 *
 * This class allows adding features like logging or encryption to tunnels
 * without modifying their core functionality.
 */
abstract class TunnelDecorator implements Tunnel {
	/**
	 * @var Tunnel The tunnel being decorated.
	 */
	protected Tunnel $decorated_tunnel;

	/**
	 * Constructor for the TunnelDecorator class.
	 *
	 * @param Tunnel $decorated_tunnel The tunnel instance to decorate.
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
	 * Gets the type of the decorated tunnel.
	 *
	 * @return string The type of the decorated tunnel.
	 */
	public function get_type(): string {
		return $this->decorated_tunnel->get_type();
	}
}

/**
 * LoggingTunnelDecorator - Decorator class that adds logging functionality to a tunnel.
 */
class LoggingTunnelDecorator extends TunnelDecorator {
	/**
	 * Starts the decorated tunnel with logging enabled.
	 */
	public function start(): void {
		parent::start();
		echo 'Logging enabled for tunnel: ' . $this->decorated_tunnel->get_type() . PHP_EOL;
	}
}

/**
 * SecureTunnelDecorator - Decorator class that adds encryption functionality to a tunnel.
 */
class SecureTunnelDecorator extends TunnelDecorator {
	/**
	 * Starts the decorated tunnel with encryption enabled.
	 */
	public function start(): void {
		parent::start();
		echo 'Encryption enabled for tunnel: ' . $this->decorated_tunnel->get_type() . PHP_EOL;
	}
}

/**
 * TunnelBuilder - Builder class for creating complex tunnel configurations.
 *
 * This class allows for a fluent interface to configure tunnels with
 * optional features like logging and encryption.
 */
class TunnelBuilder {
	/**
	 * @var Tunnel The tunnel being built.
	 */
	private Tunnel $tunnel;

	/**
	 * @var bool Flag for enabling logging.
	 */
	private bool $logging_enabled = false;

	/**
	 * @var bool Flag for enabling encryption.
	 */
	private bool $encryption_enabled = false;

	/**
	 * Constructor for the TunnelBuilder class.
	 *
	 * @param string $type The type of tunnel to create (HTTP or TCP).
	 * @param string $local_address The local address for the tunnel.
	 */
	public function __construct( string $type, string $local_address ) {
		$this->tunnel = TunnelFactory::create_tunnel( $type, $local_address );
	}

	/**
	 * Enables logging for the tunnel.
	 *
	 * @return TunnelBuilder The current instance for method chaining.
	 */
	public function enable_logging(): self {
		$this->logging_enabled = true;
		return $this;
	}

	/**
	 * Enables encryption for the tunnel.
	 *
	 * @return TunnelBuilder The current instance for method chaining.
	 */
	public function enable_encryption(): self {
		$this->encryption_enabled = true;
		return $this;
	}

	/**
	 * Builds the configured tunnel.
	 *
	 * @return Tunnel The constructed tunnel instance with applied decorators.
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

/**
 * NgrokApplication - Main application class for running the Ngrok service.
 */
class NgrokApplication {
	/**
	 * Main method to run the application.
	 */
	public static function main(): void {
		$ngrok_service = NgrokService::get_instance();

		// Create an HTTP tunnel with logging and encryption enabled.
		$http_tunnel = ( new TunnelBuilder( 'HTTP', 'http://localhost:8080' ) )
							->enable_logging()
							->enable_encryption()
							->build();

		// Create a TCP tunnel with logging enabled.
		$tcp_tunnel = ( new TunnelBuilder( 'TCP', 'tcp://localhost:9090' ) )
							->enable_logging()
							->build();

		// Add tunnels to the service.
		$ngrok_service->add_tunnel( $http_tunnel );
		$ngrok_service->add_tunnel( $tcp_tunnel );

		// Start all tunnels.
		$ngrok_service->start_all_tunnels();
	}
}

// Run the application
NgrokApplication::main();
