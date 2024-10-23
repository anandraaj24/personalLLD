<?php
namespace Enums;

/**
 * Enum for account statuses.
 */
class AccountStatus {
	const ACTIVE      = 'Active';
	const BLOCKED     = 'Blocked';
	const BANNED      = 'Banned';
	const COMPROMISED = 'Compromised';
	const ARCHIVED    = 'Archived';
	const UNKNOWN     = 'Unknown';

	public static function is_account_active( $status ) {
		return $status === self::ACTIVE;
	}
}
