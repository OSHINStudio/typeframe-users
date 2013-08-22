<?php
/**
 * User password reset controller.
 *
 * Allows users to begin the password reset process. 
 *
 * @package User
 */

// process the form
if ('POST' == $_SERVER['REQUEST_METHOD'])
{
	$users = new Model_User();
	$users->where('email = ?', $_REQUEST['email']);
	if (1 == $users->getTotal())
	{
		// get userid, resetkey, and set to expire in 1 day
		$user     = $users->getFirst();
		$userid   = $user->get('userid');
		$resetkey = randomID();
		$expire   = date('Y-m-d H:i:s', time() + 86400);

		// create an entry in the password reset table
		$reset = Model_UserReset::Create();
		$reset->set('userid',   $userid);
		$reset->set('resetkey', $resetkey);
		$reset->set('expire',   $expire);
		$reset->save();

		// construct e-mail body
		$mm = new Pagemill($pm->root()->fork());
		$mm->setVariable('username', $user->get('username'));
		$mm->setVariable('reseturl', sprintf('http://%s%s/password?userid=%d&resetkey=%s',
											$_SERVER['HTTP_HOST'], TYPEF_WEB_DIR, $userid, $resetkey));
		$body = str_replace('&amp;', '&', $mm->writeString('<pm:include template="/users/reset.eml" />', true));

		// e-mail the user so they can reset their password
		$mailer = new Mailer();
		$mailer->Configure();
		$mailer->IsHTML(true);
		$mailer->AddAddress($_POST['email']);
		$mailer->Subject = ('Request to Reset Password for ' . TYPEF_TITLE);
		$mailer->Body    = $body;
		$mailer->Send();
		$pm->setVariable('reset_email_sent', true);
		Typeframe::Log('Request to reset password for ' . $_POST['email']);
	}
	else
	{
		$pm->setVariable('reset_email_failed', true);
	}
}

// set template (controller is at root, but template lives in users directory
Typeframe::SetPageTemplate('/users/password-reset.html');
