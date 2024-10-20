<?php

// Singleton for managing NGROK Service
class NgrokService {
	private static ?NgrokService $instance = null;
	private array $tunnels                 = array();

	private function __construct() {}

	public static function get_instance(): NgrokService {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __clone() {}
	private function __wakeup() {}

	public function add_tunnel( Tunnel $tunnel ): void {
		$this->tunnels[] = $tunnel;
		echo 'Tunnel added: ' . $tunnel->get_type() . PHP_EOL;
	}

	public function start_all_tunnels(): void {
		foreach ( $this->tunnels as $tunnel ) {
			$tunnel->start();
		}
	}
}

// Factory for creating tunnels
class TunnelFactory {
	public static function create_tunnel( string $type, string $local_address ): Tunnel {
		self::validate_local_address( $local_address );

		return match ( strtoupper( $type ) ) {
			'HTTP' => new HttpTunnel( $local_address ),
			'TCP' => new TcpTunnel( $local_address ),
			default => throw new InvalidArgumentException( "Unknown tunnel type: $type" ),
		};
	}

	private static function validate_local_address( string $local_address ): void {
		if ( ! filter_var( $local_address, FILTER_VALIDATE_URL ) ) {
			throw new InvalidArgumentException( "Invalid local address: $local_address" );
		}
	}
}

// Tunnel Interface
interface Tunnel {
	public function start(): void;
	public function get_type(): string;
}

// Concrete Tunnel Implementations
class HttpTunnel implements Tunnel {
	private string $local_address;

	public function __construct( string $local_address ) {
		$this->local_address = $local_address;
	}

	public function start(): void {
		echo 'Starting HTTP Tunnel on ' . $this->local_address . PHP_EOL;
	}

	public function get_type(): string {
		return 'HTTP';
	}
}

class TcpTunnel implements Tunnel {
	private string $local_address;

	public function __construct( string $local_address ) {
		$this->local_address = $local_address;
	}

	public function start(): void {
		echo 'Starting TCP Tunnel on ' . $this->local_address . PHP_EOL;
	}

	public function get_type(): string {
		return 'TCP';
	}
}

// Observer for logging and monitoring
interface TunnelObserver {
	public function update( Tunnel $tunnel, string $event ): void;
}

class LoggingObserver implements TunnelObserver {
	public function update( Tunnel $tunnel, string $event ): void {
		echo "Logging event: $event on tunnel " . $tunnel->get_type() . PHP_EOL;
	}
}

class MonitoringObserver implements TunnelObserver {
	public function update( Tunnel $tunnel, string $event ): void {
		echo "Monitoring event: $event on tunnel " . $tunnel->get_type() . PHP_EOL;
	}
}

// Strategy for load balancing
interface LoadBalancingStrategy {
	public function select_tunnel( array $tunnels ): Tunnel;
}

class RoundRobinStrategy implements LoadBalancingStrategy {
	private int $current_index = 0;

	public function select_tunnel( array $tunnels ): Tunnel {
		$tunnel              = $tunnels[ $this->current_index ];
		$this->current_index = ( $this->current_index + 1 ) % count( $tunnels );
		return $tunnel;
	}
}

class LeastConnectionsStrategy implements LoadBalancingStrategy {
	public function select_tunnel( array $tunnels ): Tunnel {
		return $tunnels[0]; // Simplified for this example
	}
}

// Decorator for adding features like logging, access control, etc.
abstract class TunnelDecorator implements Tunnel {
	protected Tunnel $decorated_tunnel;

	public function __construct( Tunnel $decorated_tunnel ) {
		$this->decorated_tunnel = $decorated_tunnel;
	}

	public function start(): void {
		$this->decorated_tunnel->start();
	}

	public function get_type(): string {
		return $this->decorated_tunnel->get_type();
	}
}

class LoggingTunnelDecorator extends TunnelDecorator {
	public function start(): void {
		parent::start();
		echo 'Logging enabled for tunnel: ' . $this->decorated_tunnel->get_type() . PHP_EOL;
	}
}

class SecureTunnelDecorator extends TunnelDecorator {
	public function start(): void {
		parent::start();
		echo 'Encryption enabled for tunnel: ' . $this->decorated_tunnel->get_type() . PHP_EOL;
	}
}

// Builder for creating complex tunnel configurations
class TunnelBuilder {
	private Tunnel $tunnel;
	private bool $logging_enabled    = false;
	private bool $encryption_enabled = false;

	public function __construct( string $type, string $local_address ) {
		$this->tunnel = TunnelFactory::create_tunnel( $type, $local_address );
	}

	public function enable_logging(): self {
		$this->logging_enabled = true;
		return $this;
	}

	public function enable_encryption(): self {
		$this->encryption_enabled = true;
		return $this;
	}

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

// Main Application
class NgrokApplication {
	public static function main(): void {
		$ngrok_service = NgrokService::get_instance();

		$http_tunnel = ( new TunnelBuilder( 'HTTP', 'http://localhost:8080' ) )
							->enable_logging()
							->enable_encryption()
							->build();

		$tcp_tunnel = ( new TunnelBuilder( 'TCP', 'tcp://localhost:9090' ) )
							->enable_logging()
							->build();

		$ngrok_service->add_tunnel( $http_tunnel );
		$ngrok_service->add_tunnel( $tcp_tunnel );

		$ngrok_service->start_all_tunnels();
	}
}

// Run the application
NgrokApplication::main();
