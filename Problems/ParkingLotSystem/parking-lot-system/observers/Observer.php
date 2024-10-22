<?php
namespace Observers;

/**
 * Interface for observers.
 */
interface Observer {
    public function update($message);
}
