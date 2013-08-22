<?php
interface AuthInterface {
	public function __construct(Dbi_Record $user);
	/**
	 * Validate a login.
	 * @param mixed $key A value (e.g., password or token) to be used for authentication.
	 * @return boolean True if login succeeded.
	 */
	public function validate($key);
}
