<?php

namespace NgrokService;

/**
 * RoundRobinStrategy - Load balancing strategy that selects tunnels in a round-robin manner.
 */
class RoundRobinStrategy implements LoadBalancingStrategy {
	/**
	 * This tracks the current position in the tunnel array.
	 *
	 * @var int Current index for round-robin selection.
	 */
	private int $current_index = 0;

	/**
	 * Selects a tunnel using the round-robin strategy.
	 *
	 * @param Tunnel[] $tunnels The list of available tunnels.
	 * @return Tunnel The selected tunnel.
	 */
	public function select_tunnel( array $tunnels ): Tunnel {
		$tunnel              = $tunnels[ $this->current_index ];
		$this->current_index = ( $this->current_index + 1 ) % count( $tunnels );
		return $tunnel;
	}
}
