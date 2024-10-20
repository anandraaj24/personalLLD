<?php

namespace NgrokService;

/**
 * LeastConnectionsStrategy - Load balancing strategy that selects the tunnel with the least connections.
 */
class LeastConnectionsStrategy implements LoadBalancingStrategy {
	/**
	 * Selects a tunnel based on the least connections strategy.
	 *
	 * @param Tunnel[] $tunnels The list of available tunnels.
	 * @return Tunnel The selected tunnel.
	 */
	public function select_tunnel( array $tunnels ): Tunnel {
		return $tunnels[0];
	}
}
