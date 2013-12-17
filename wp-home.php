<?php
/*
Plugin Name: Wp home management
Description: Manage your bank accounts, the contracts of regular consumption of a home
Version: 0.1
Author: Alexandre Techer
*/

/**
 * Bootstrap file
 *
 * @author Alexandre Techer <alexandre.techer@gmail.com>
 * @version 0.1
 */

/**
 * Define the current version for the plugin. Interresting for clear cache for plugin style and script
 * @var string Plugin current version number
 */
DEFINE('WP_HOME_MNGT_VERSION', '0.1');

/**
 * Get the plugin main dirname. Allows to avoid writing path directly into code
 * @var string Dirname of the plugin
 */
DEFINE('WP_HOME_MNGT_DIR', basename(dirname(__FILE__)));

/**	Include config file */
require_once(WP_PLUGIN_DIR . '/' . WP_HOME_MNGT_DIR . '/core/config.php' );

/**	Allows php notice/fatal errors debugging	*/
if ( WPHMGT_DEBUG_MODE && in_array(long2ip(ip2long($_SERVER['REMOTE_ADDR'])), unserialize(WPHMGT_DEBUG_ALLOWED_IP)) ) {
	ini_set( 'display_errors', true );
	error_reporting( E_ALL );
}

/** Include all librairies on plugin load */
require_once( WP_HOME_MNGT_CORELIBS_DIR . '/files_include.php' );

/** Plugin initialisation */
$wphmmnt = new wphmngt_home();

/**	Create default content on plugin first activiation	*/
register_activation_hook( __FILE__, array( $wphmmnt, 'activation' ) );

?>