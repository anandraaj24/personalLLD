<?php
// phpcs:ignoreFile

// Singleton for managing NGROK Service
class NgrokService {
	private static ?NgrokService $instance = null;
	private array $tunnels                 = array();

	private function __construct() {}

	public static function getInstance(): NgrokService {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __clone() {}
	private function __wakeup() {}

	public function addTunnel( Tunnel $tunnel ): void {
		$this->tunnels[] = $tunnel;
		echo 'Tunnel added: ' . $tunnel->getType() . PHP_EOL;
	}

	public function startAllTunnels(): void {
		foreach ( $this->tunnels as $tunnel ) {
			$tunnel->start();
		}
	}
}

// Factory for creating tunnels
class TunnelFactory {
	public static function createTunnel( string $type, string $localAddress ): Tunnel {
		self::validateLocalAddress( $localAddress );

		return match ( strtoupper( $type ) ) {
			'HTTP' => new HttpTunnel( $localAddress ),
			'TCP' => new TcpTunnel( $localAddress ),
			default => throw new InvalidArgumentException( "Unknown tunnel type: $type" ),
		};
	}

	private static function validateLocalAddress( string $localAddress ): void {
		if ( ! filter_var( $localAddress, FILTER_VALIDATE_URL ) ) {
			throw new InvalidArgumentException( "Invalid local address: $localAddress" );
		}
	}
}

// Tunnel Interface
interface Tunnel {
	public function start(): void;
	public function getType(): string;
}

// Concrete Tunnel Implementations
class HttpTunnel implements Tunnel {
	private string $localAddress;

	public function __construct( string $localAddress ) {
		$this->localAddress = $localAddress;
	}

	public function start(): void {
		echo 'Starting HTTP Tunnel on ' . $this->localAddress . PHP_EOL;
	}

	public function getType(): string {
		return 'HTTP';
	}
}

class TcpTunnel implements Tunnel {
	private string $localAddress;

	public function __construct( string $localAddress ) {
		$this->localAddress = $localAddress;
	}

	public function start(): void {
		echo 'Starting TCP Tunnel on ' . $this->localAddress . PHP_EOL;
	}

	public function getType(): string {
		return 'TCP';
	}
}

// Observer for logging and monitoring
interface TunnelObserver {
	public function update( Tunnel $tunnel, string $event ): void;
}

class LoggingObserver implements TunnelObserver {
	public function update( Tunnel $tunnel, string $event ): void {
		echo "Logging event: $event on tunnel " . $tunnel->getType() . PHP_EOL;
	}
}

class MonitoringObserver implements TunnelObserver {
	public function update( Tunnel $tunnel, string $event ): void {
		echo "Monitoring event: $event on tunnel " . $tunnel->getType() . PHP_EOL;
	}
}

// Strategy for load balancing
interface LoadBalancingStrategy {
	public function selectTunnel( array $tunnels ): Tunnel;
}

class RoundRobinStrategy implements LoadBalancingStrategy {
	private int $currentIndex = 0;

	public function selectTunnel( array $tunnels ): Tunnel {
		$tunnel             = $tunnels[ $this->currentIndex ];
		$this->currentIndex = ( $this->currentIndex + 1 ) % count( $tunnels );
		return $tunnel;
	}
}

class LeastConnectionsStrategy implements LoadBalancingStrategy {
	public function selectTunnel( array $tunnels ): Tunnel {
		return $tunnels[0]; // Simplified for this example
	}
}

// Decorator for adding features like logging, access control, etc.
abstract class TunnelDecorator implements Tunnel {
	protected Tunnel $decoratedTunnel;

	public function __construct( Tunnel $decoratedTunnel ) {
		$this->decoratedTunnel = $decoratedTunnel;
	}

	public function start(): void {
		$this->decoratedTunnel->start();
	}

	public function getType(): string {
		return $this->decoratedTunnel->getType();
	}
}

class LoggingTunnelDecorator extends TunnelDecorator {
	public function start(): void {
		parent::start();
		echo 'Logging enabled for tunnel: ' . $this->decoratedTunnel->getType() . PHP_EOL;
	}
}

class SecureTunnelDecorator extends TunnelDecorator {
	public function start(): void {
		parent::start();
		echo 'Encryption enabled for tunnel: ' . $this->decoratedTunnel->getType() . PHP_EOL;
	}
}

// Builder for creating complex tunnel configurations
class TunnelBuilder {
	private Tunnel $tunnel;
	private bool $loggingEnabled    = false;
	private bool $encryptionEnabled = false;

	public function __construct( string $type, string $localAddress ) {
		$this->tunnel = TunnelFactory::createTunnel( $type, $localAddress );
	}

	public function enableLogging(): self {
		$this->loggingEnabled = true;
		return $this;
	}

	public function enableEncryption(): self {
		$this->encryptionEnabled = true;
		return $this;
	}

	public function build(): Tunnel {
		if ( $this->loggingEnabled ) {
			$this->tunnel = new LoggingTunnelDecorator( $this->tunnel );
		}
		if ( $this->encryptionEnabled ) {
			$this->tunnel = new SecureTunnelDecorator( $this->tunnel );
		}
		return $this->tunnel;
	}
}

// Main Application
class NgrokApplication {
	public static function main(): void {
		$ngrokService = NgrokService::getInstance();

		$httpTunnel = ( new TunnelBuilder( 'HTTP', 'http://localhost:8080' ) )
							->enableLogging()
							->enableEncryption()
							->build();

		$tcpTunnel = ( new TunnelBuilder( 'TCP', 'tcp://localhost:9090' ) )
							->enableLogging()
							->enableEncryption()
							->build();

		$ngrokService->addTunnel( $httpTunnel );
		$ngrokService->addTunnel( $tcpTunnel );

		$ngrokService->startAllTunnels();
	}
}

// Run the application
NgrokApplication::main();
