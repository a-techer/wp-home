<?php
/**
 * Account management librairies
 *
 * @author Alexandre Techer <alexandre.techer@gmail.com>
 * @version 0.1
 * @package wp-home
 * @subpackage modules
 */

/** Check if the plugin version is defined. If not defined script will be stopped here */
if ( !defined( 'WP_HOME_MNGT' ) ) {
	die( __("You are not allowed to use this service.", 'wp_home_mngt') );
}

/**
 * Account management librairies
 *
 * @author Alexandre Techer <alexandre.techer@gmail.com>
 * @version 0.1
 * @package wp-home
 * @subpackage modules
 */
class wphmngt_account extends wphmngt_display {

	function __construct() {
		parent::_construct();
	}

}

?>