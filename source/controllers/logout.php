<?php
/**
 * User logout controller.
 *
 * Provides a logout form and logs the user out.
 *
 * @package User
 */

// process form
if ('POST' == $_SERVER['REQUEST_METHOD'])
{
	Typeframe::User()->logout();
	Typeframe::Redirect('Logout complete.', TYPEF_WEB_DIR . '/');
	return;
}

// set template (controller is at root, but template lives in users directory
Typeframe::SetPageTemplate('/users/logout.html');
?>