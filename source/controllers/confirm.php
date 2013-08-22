<?php
/**
 * User confirmation controller.
 *
 * This has dual purpose of confirming a user's email and logging in that user.
 *
 * @package User
 */

// clear expired confirmations
UserConfirm::Delete_Expired();

// get userid, confirmkey
$userid     = trim(@$_REQUEST['userid']);
$confirmkey = trim(@$_REQUEST['confirmkey']);

// count confirmations for userid-confirmkey; load user
$confirm_count = UserConfirm::Count($userid, $confirmkey);
$user = new User($userid);

// if no confirmations or invalid user, report error
if ((0 == $confirm_count) || !$user->exists())
{
	Typeframe::SetPageTemplate('/users/register/confirmation-failed.html');
	return;
}

// otherwise, update confirmed flag and save user
$user->set('confirmed', 1);
$user->save();

// clear user from confirmations
UserConfirm::Delete_User($userid);

// set user in session
$_SESSION['typef_user'] = $user->getAsArray();

// done
Typeframe::Redirect('Your account confirmation is complete.  Welcome!', (TYPEF_WEB_DIR . '/'), 1);
return;
?>
