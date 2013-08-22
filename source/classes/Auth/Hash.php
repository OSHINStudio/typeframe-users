<?php
class Auth_Hash extends Auth {
	/**
	 * Validate the provided password.
	 * @param string $password
	 * @return bool
	 */
	public function validate($password) {
		if (!$this->user->exists()) return false;
		switch ($this->user['hashtype']) {
			case 'md5':
				return (md5("{$password}{$this->user['salt']}") == $this->user['passhash']);
				break;
			case 'sha1':
				return (sha1("{$password}{$this->user['salt']}") == $this->user['passhash']);
				break;
			case 'bcrypt':
				return (crypt($password, $this->user['salt']) == $this->user['passhash']);
				break;
			default:
				throw new Exception("Unrecognized hash type '{$this->user['hashtype']}'");
		}
	}
}
