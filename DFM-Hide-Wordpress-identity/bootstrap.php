<?php
/*
Plugin Name: DFM Hide Wordpress Identity
Plugin URI:  https://github.com/iandunn/WordPress-Plugin-Skeleton
Description: The skeleton for an object-oriented/MVC WordPress plugin
Version:     0.1.3
Author:      Touhidur Rahaman
Author URI:  http://touhid.name
*/

if (!defined('ABSPATH')) {
	die('Access denied.');
}

define('DFM_NAME', 'DFM Hide Wordpress Identity');
define('DFM_REQUIRED_PHP_VERSION', '5.3');
define('DFM_REQUIRED_WP_VERSION',  '4.1');

const PREFIX     = 'dfm_';
const VERSION    = '0.1';

/**
 * Checks if the system requirements are met
 *
 * @return bool True if system requirements are met, false if not
 */
function dmf_requirements_met()
{
	global $wp_version;
	//require_once( ABSPATH . '/wp-admin/includes/plugin.php' );		// to get is_plugin_active() early

	if (version_compare(PHP_VERSION, DFM_REQUIRED_PHP_VERSION, '<')) {
		return false;
	}

	if (version_compare($wp_version, DFM_REQUIRED_WP_VERSION, '<')) {
		return false;
	}

	/*
	if ( ! is_plugin_active( 'plugin-directory/plugin-file.php' ) ) {
		return false;
	}
	*/

	return true;
}

/**
 * Prints an error that the system requirements weren't met.
 */
function dmf_requirements_error()
{
	global $wp_version;

	require_once(dirname(__FILE__) . '/views/requirements-error.php');
}

/*
 * Check requirements and load main class
 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met. Otherwise older PHP installations could crash when trying to parse it.
 */
if (dmf_requirements_met()) {
	require_once(__DIR__ . '/includes/hide-login-logo.php');
	require_once(__DIR__ . '/includes/dfm-hide-login-logo-settings.php');
} else {
	add_action('admin_notices', 'dmf_requirements_error');
}
