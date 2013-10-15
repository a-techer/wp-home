<?php
/**
 * Plugin initialisation file
 *
 * @author Alexandre Techer <alexandre.techer@gmail.com>
 * @version 0.1
 * @package wp-home
 * @subpackage core
 */

/** Check if the plugin version is defined. If not defined script will be stopped here */
if ( !defined( 'WP_HOME_MNGT' ) ) {
	die( __("You are not allowed to use this service.", 'wp_home_mngt') );
}

/**
 * Plugin initialisation class
 *
 * @author Alexandre Techer <alexandre.techer@gmail.com>
 * @version 0.1
 * @package wp-home
 * @subpackage core
 */
class wphmngt_home extends wphmngt_display {

	/**
	 * Instanciate plugin main controller.
	 * Call the different function allowing to manage the plugin
	 */
	function __construct() {
		/**	Instanciate display	*/
		parent::__construct();

		/** call plugin menu initialisation */
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
	}

	/**
	 * Create a new item into admin menu for the plugin
	 */
	function admin_menu() {
		add_menu_page( __('Home management dashboard', 'wp_home_mngt'), __('Home', 'wp_home_mngt'), 'manage_options', /* WP_HOME_MNGT_DIR . '/templates/backend/dashboard.tpl.php' */'wp-home-dashboard', array( &$this, 'dashboard' ) );
	}

	/**
	 * Define the dashboard controller function
	 */
	function dashboard() {
		$dashboard_content = array();

		ob_start();
			/**	Create nonce for metabox order saving securisation	*/
			wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false);
			/**	Create nonce for metabox order saving securisation	*/
			wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false);

			/**	Call the different fonction to add meta boxes on dashboard	*/
			$this->dashboard_metaboxes_caller();
			do_meta_boxes("wphmngt-dashboard", "wphmngt-dashboard-top", null);
			do_meta_boxes("wphmngt-dashboard", "wphmngt-dashboard-left", null);
			do_meta_boxes("wphmngt-dashboard", "wphmngt-dashboard-right", null);
			$dashboard_content[ 'DASHBOARD' ] = ob_get_contents();
		ob_end_clean();

		echo $this->display( 'dashboard_layout' , $dashboard_content );
	}

	function dashboard_metaboxes_caller() {

	}

}

?>