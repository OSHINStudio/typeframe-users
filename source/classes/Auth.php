<?php
abstract class Auth implements AuthInterface {
	protected $user;
	public function __construct(Dbi_Record $user) {
		if (!is_a($user->model(), 'Model_User')) {
			throw new Exception('Record used for Auth must be a User');
		}
		$this->user = $user;
	}
	public function user() {
		return $this->user;
	}
	/**
	 * Validate an authentication attempt.
	 * @param mixed $key A value (e.g., password or token) to be used for authentication.
	 */
	abstract public function validate($key);
	/**
	 * Create an Auth object based on a user's auth setting or the default authentication method.
	 * @param string $usernameOrEmail The user name or email address of the user.
	 * @param string $field Which field to use (username, email, or either)
	 * @param string $method Default authentication method for nonexistent users (e.g., so users can authenticate through LDAP even if they don't have a user record yet)
	 * @return Auth
	 */
	public static function ForUser($usernameOrEmail, $field = 'either', $method = TYPEF_AUTH_DEFAULT) {
		switch ($field) {
			case 'username':
				$field = 'username';
				break;
			case 'email':
				$field = 'email';
				break;
			default:
				$field = 'username';
				if (preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $usernameOrEmail)) {
					$field = 'email';
				}
				break;
		}
		$users = new Model_User();
		$users->where("{$field} = ?", $usernameOrEmail);
		$obj = null;
		if ($users->getTotal() == 0) {
			$user = Model_User::Create();
			$user[$field] = $usernameOrEmail;
			$cls = 'Auth_' . $method;
		} else {
			if ($users->getTotal() > 1) {
				Typeframe::Log("WARNING: {$usernameOrEmail} matches more than one {$field} in the user table.");
			}
			$user = $users->getFirst();
			$userAuth = $user['auth'];
			if (!$userAuth) $userAuth = 'Hash'; // Older users might have a blank auth field. Assume Hash
			$cls = 'Auth_' . $userAuth;
		}
		if (!is_subclass_of($cls, 'Auth')) {
			throw new Exception("{$cls} is not a subclass of Auth");
		}
		$obj = new $cls($user);
		return $obj;
	}
}
