<?php

namespace NgrokService;

require_once 'vendor/autoload.php';
/**
 * NgrokApplication - Main application class for running the Ngrok service.
 */
class NgrokApplication {
	/**
	 * Entry point of the application.
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
							->enable_encryption()
							->build();

		// Add tunnels to the service.
		$ngrok_service->add_tunnel( $http_tunnel );
		$ngrok_service->add_tunnel( $tcp_tunnel );

		// Start all tunnels.
		$ngrok_service->start_all_tunnels();
	}
}

// Run the application.
NgrokApplication::main();
