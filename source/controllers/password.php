<?php
/**
 * User new password controller.
 *
 * Allows users to complete the password reset process. 
 *
 * @package User
 */

// delete expired resets

// get userid, resetkey
$userid   = trim(@$_REQUEST['userid']);
$resetkey = trim(@$_REQUEST['resetkey']);

// count resets for userid-resetkey; load user
$reset = Model_UserReset::Get(array('userid' => $userid, 'resetkey' => $resetkey));
$user = Model_User::Get($userid);

// if no resets or invalid user, report error
if (!$reset->exists() || !$user->exists()){
	Typeframe::Redirect('Invalid reset key.', (TYPEF_WEB_DIR . '/'), -1);
	return;
}

// process form
if ('POST' == $_SERVER['REQUEST_METHOD']){
	// get password and password2
	$password  = trim(@$_POST['password']);
	$password2 = trim(@$_POST['password2']);

	// check for errors
	if (!strlen($password) && !strlen($password2)){
		$pm->addLoop('errors', array('message' => 'A password is required.'));
	} elseif($password != $password2){
		$pm->addLoop('errors', array('message' => 'The passwords you entered did not match.'));
	}else{
		// reset it for this user
		$user->set('password', $password);
		$user->save();
		$reset->delete();

		Typeframe::User()->login($user->get('username'), $password);
		Typeframe::Log('Password changed.');
		Typeframe::Redirect('Your password has been reset.', (TYPEF_WEB_DIR . '/'), 1);
		return;
	}
}

$pm->setVariable('userid',   $userid);
$pm->setVariable('username', $user->get('username'));
$pm->setVariable('resetkey', $resetkey);

// set template (controller is at root, but template lives in users directory
Typeframe::SetPageTemplate('/users/password-new.html');
