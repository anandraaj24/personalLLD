<?php
namespace Strategies;

/**
 * Interface for payment strategies.
 */
interface PaymentStrategy {
    public function pay($ticket);
}
