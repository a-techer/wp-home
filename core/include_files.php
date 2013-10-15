<?php
/**
 * Inclusion file
 *
 * @author Alexandre Techer <alexandre.techer@gmail.com>
 * @version 0.1
 * @package wp-home
 * @subpackage core
 */

/** Check if the plugin version is defined. If not defined script will be stopped here	*/
if ( !defined( 'WP_HOME_MNGT' ) ) {
	die( __("You are not allowed to use this service.", 'wp_home_mngt') );
}

/**	Include display component	*/
require_once(WP_HOME_MNGT_CORELIBS_DIR . '/display.class.php' );

/**	Include plugin main controller file	*/
require_once(WP_HOME_MNGT_CORELIBS_DIR . '/wp_home.class.php' );

/**	Include account management librairies 	*/
require_once(WP_HOME_MNGT_MODULESLIBS_DIR . '/wp_account.class.php' );