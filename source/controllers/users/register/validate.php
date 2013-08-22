<?php
/**
 * User account register/validate controller.
 *
 * @package User
 */

if ('POST' == $_SERVER['REQUEST_METHOD'])
{
	$user = new User();
	$form = new FormHandler_User($user);
	$form->validateAjaxPost();
}
?>
