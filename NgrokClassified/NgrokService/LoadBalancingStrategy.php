<?php

namespace NgrokService;

/**
 * LoadBalancingStrategy - Interface for tunnel load balancing strategies.
 */
interface LoadBalancingStrategy {
	/**
	 * Selects a tunnel from the given list based on the strategy.
	 *
	 * @param Tunnel[] $tunnels The list of available tunnels.
	 * @return Tunnel The selected tunnel.
	 */
	public function select_tunnel( array $tunnels ): Tunnel;
}
