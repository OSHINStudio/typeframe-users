<?php
// Add user data to Pagemill
// TODO: This trigger isn't strictly necessary anymore, since it's easy to pass get/post/session/cookie data into templates
// (and as of this writing, the kernel.php trigger does so)
Typeframe::Pagemill()->setVariable('loggedin', Typeframe::User()->loggedIn());
if (Typeframe::User()->loggedIn()) {
	Typeframe::Pagemill()->setVariable('typef_session_username', Typeframe::User()->get('username'));
	Typeframe::Pagemill()->setVariable('typef_session_userid', Typeframe::User()->get('userid'));
	Typeframe::Pagemill()->setVariable('typef_session_usergroupid', Typeframe::User()->get('usergroupid'));
}
