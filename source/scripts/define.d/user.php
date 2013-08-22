<?php
/**
 * This script was automatically generated. Instead of modifying it directly,
 * the best practice is to modify the corresponding <config> element in the
 * Typeframe registry and regenerate this script with the tfadmin.php tool.
 *
 * The primary purpose of this script is to document the constants defined in
 * the application registry so they are discoverable in IDEs.
 */
 

/**
 * Allow new account registration (default: '1')
 */
define('TYPEF_ALLOW_REGISTRATION', Typeframe::Registry()->getConfigValue('TYPEF_ALLOW_REGISTRATION'));

/**
 * Allow users to store login cookies (default: '1')
 */
define('TYPEF_ALLOW_LOGIN_COOKIE', Typeframe::Registry()->getConfigValue('TYPEF_ALLOW_LOGIN_COOKIE'));

/**
 * # days to store login cookies (default: '90')
 */
define('TYPEF_LOGIN_COOKIE_EXPIRE', Typeframe::Registry()->getConfigValue('TYPEF_LOGIN_COOKIE_EXPIRE'));

/**
 * Require email confirmation (default: '1')
 */
define('TYPEF_REQUIRE_CONFIRMATION', Typeframe::Registry()->getConfigValue('TYPEF_REQUIRE_CONFIRMATION'));

/**
 * Allow temp login without confirmation (default: '0')
 */
define('TYPEF_TEMP_AFTER_REG', Typeframe::Registry()->getConfigValue('TYPEF_TEMP_AFTER_REG'));

/**
 * Require admin approval to activate accounts (default: '0')
 */
define('TYPEF_REQUIRE_APPROVAL', Typeframe::Registry()->getConfigValue('TYPEF_REQUIRE_APPROVAL'));

/**
 * Email users when admin approval is required (default: '0')
 */
define('TYPEF_REQUIRE_APPROVAL_NOTIFICATION', Typeframe::Registry()->getConfigValue('TYPEF_REQUIRE_APPROVAL_NOTIFICATION'));

/**
 * Email these addresses when a user registers (default: '')
 */
define('TYPEF_REGISTRATION_NOTIFICATION', Typeframe::Registry()->getConfigValue('TYPEF_REGISTRATION_NOTIFICATION'));

/**
 * Authentication mechanism to use by default (default: 'Hash')
 */
define('TYPEF_AUTH_DEFAULT', Typeframe::Registry()->getConfigValue('TYPEF_AUTH_DEFAULT'));
