<?php

namespace NgrokService;

/**
 * NgrokService - Singleton class for managing NGROK tunnels.
 *
 * This class allows for adding and starting multiple tunnels.
 */
class NgrokService {
	/**
	 * This holds the instance of the NgrokService.
	 *
	 * @var NgrokService|null Singleton instance of the service.
	 */
	private static ?NgrokService $instance = null;

	/**
	 * This stores all tunnels that have been added to the service.
	 *
	 * @var Tunnel[] Array to hold added tunnels.
	 */
	private array $tunnels = array();

	/**
	 * Private constructor to prevent direct instantiation.
	 */
	private function __construct() {}

	/**
	 * Returns the singleton instance of NgrokService.
	 *
	 * @return NgrokService The singleton instance.
	 */
	public static function get_instance(): NgrokService {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Prevents cloning of the instance.
	 */
	private function __clone() {}

	/**
	 * Prevents unserializing the instance.
	 */
	public function __wakeup() {}

	/**
	 * Adds a tunnel to the service.
	 *
	 * @param Tunnel $tunnel The tunnel to add.
	 */
	public function add_tunnel( Tunnel $tunnel ): void {
		$this->tunnels[] = $tunnel;
		echo 'Tunnel added: ' . $tunnel->get_type() . PHP_EOL;
	}

	/**
	 * Starts all registered tunnels.
	 */
	public function start_all_tunnels(): void {
		foreach ( $this->tunnels as $tunnel ) {
			$tunnel->start();
		}
	}
}
