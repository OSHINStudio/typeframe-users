<?php
Typeframe::SetPageTemplate('/users/login.html');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$auth = Auth::ForUser($_POST['username']);
	try {
		$valid = $auth->validate($_POST['password']);
	} catch(Exception $e) {
		$valid = false;
	}
	if ($valid) {
		$user = $auth->user();
		$_SESSION['typef_user'] = $user->getArray(false);
		if (!empty($_POST['remember'])) {
			// Store cookie
			setcookie('typef_userid', $user['userid'], time() + (60 * 60 * 24 * 30), '/');
			setcookie('typef_passhash', $user['passhash'], time() + (60 * 60 * 24 * 30), '/');
		}
		if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '/login') === false) {
			$redirect = $_SERVER['HTTP_REFERER'];
		} else {
			$redirect = TYPEF_WEB_DIR . '/';
		}
		Typeframe::Redirect('Login Successful! Welcome!', $redirect);
	} else {
		$pm->addLoop('errors', array('message' => 'Invalid login.'));
	}
}
