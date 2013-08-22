<?php
/**
 * User account register/index controller.
 *
 * Allows users to register. Sends emails in the event of confirmation or approval being required. 
 *
 * @package User
 */

// if registration is disabled, then we're done here
if (TYPEF_ALLOW_REGISTRATION == 0)
{
	Typeframe::Redirect('Registration has been disabled.', (TYPEF_WEB_DIR . '/'), -1);
	return;
}

// create user and a formhandler for them
$user = new User();
$form = new FormHandler_User($user);

// process the form
if ('POST' == $_SERVER['REQUEST_METHOD'])
{
	// validate the form; get errors
	$form->validate();
	$errors = $form->errors();
	if (count($errors))
	{
		// add errors to template
		$pm->setVariable('errors', $errors);
	}
	else
	{
		// set user values and save
		$salt     = randomID();
		$hashtype = 'sha1';
		$password = $hashtype("{$_POST['password']}$salt");
		$user->set('username',    $_POST['username']);
		$user->set('password',    $password);
		$user->set('hashtype',    $hashtype);
		$user->set('salt',        $salt);
		$user->set('email',       $_POST['email']);
		$user->set('usergroupid', TYPEF_DEFAULT_USERGROUPID);
		$user->set('firstname',   $_POST['firstname']);
		$user->set('lastname',    $_POST['lastname']);
		$user->save();

		// get defined admin notification addresses
		$admins = array();
		foreach (explode(',', TYPEF_REGISTRATION_NOTIFICATION) as $email)
		{
			$email = filter_var(trim($email), FILTER_VALIDATE_EMAIL);
			if (false !== $email) $admins[] = $email;
		}

		// notify admins
		if (!empty($admins))
		{
			// construct e-mail body
			$mm = new Pagemill($pm->root()->fork());
			if (TYPEF_REQUIRE_APPROVAL) $mm->setVariable('typef_require_approval', true);
			$mm->setVariable('approvalurl', sprintf('http://%s%s/admin/users/edit?userid=%d', $_SERVER['HTTP_HOST'], TYPEF_WEB_DIR, $user->get('userid')));
			$body = str_replace('&amp;', '&', $mm->writeText('<pm:include template="/users/register/admin-notification.eml" />'));

			// e-mail the admins
			$mailer = new TypeframeMailer();
			$mailer->Configure();
			$mailer->IsHTML(true);
			foreach ($admins as $email) $mailer->AddAddress($email);
			$mailer->Subject = ('New User Registration at ' . TYPEF_TITLE);
			$mailer->Body    = $body;
			$mailer->Send();
		}

		if (TYPEF_REQUIRE_APPROVAL)
		{
			// notify the user that admin approval is required
			if (TYPEF_REQUIRE_APPROVAL_NOTIFICATION)
			{
				// construct e-mail body
				$mm = new Pagemill($pm->root()->fork());
				$body = str_replace('&amp;', '&', $mm->writeText('<pm:include template="/users/register/pre-approval.eml" />'));

				// e-mail the user
				$mailer = new TypeframeMailer();
				$mailer->Configure();
				$mailer->IsHTML(true);
				$mailer->AddAddress($_POST['email']);
				$mailer->Subject = ('Your Account Pre-Approval from ' . TYPEF_TITLE);
				$mailer->Body    = $body;
				$mailer->Send();
			}

			// set flag in template
			$pm->setVariable('typef_require_approval', true);
		}
		elseif (TYPEF_REQUIRE_CONFIRMATION)
		{
			// set flag in template
			$pm->setVariable('typef_require_confirmation', true);

			// get userid, confirmkey, and set to expire in 3 days
			$userid     = $user->get('userid');
			$confirmkey = randomID();
			$expire     = date('Y-m-d H:i:s', (time() + 259200));

			// create an entry in the user confirmation table
			$user_confirm = new UserConfirm();
			$user_confirm->set('confirmkey', $confirmkey);
			$user_confirm->set('userid',     $userid);
			$user_confirm->set('expire',     $expire);
			$user_confirm->save();

			// construct e-mail body
			$mm = new Pagemill($pm->root()->fork());
			$mm->setVariable('username', $user->get('username'));
			$mm->setVariable('confirmurl', sprintf('http://%s%s/confirm?userid=%d&confirmkey=%s',
												$_SERVER['HTTP_HOST'], TYPEF_WEB_DIR, $userid, $confirmkey));
			$body = str_replace('&amp;', '&', $mm->writeText('<pm:include template="/users/register/confirmation.eml" />'));

			// e-mail the user so they can confirm their registration
			$mailer = new TypeframeMailer();
			$mailer->Configure();
			$mailer->IsHTML(true);
			$mailer->AddAddress($_POST['email']);
			$mailer->Subject = ('Your Account Confirmation from ' . TYPEF_TITLE);
			$mailer->Body    = $body;
			$mailer->Send();
		}
		else
		{
			// if confirmation is not required, log the user in immediately
			Typeframe::User()->login($_POST['username'], $_POST['password']);
			Typeframe::Redirect('Registration complete.  Welcome!', (TYPEF_WEB_DIR . '/'), 1);
			return;
		}

		// registratino is complete
		Typeframe::SetPageTemplate('/users/register/complete.html');
	}
}

// populate form fields
$pm->setVariable('fields', $form->fields());
