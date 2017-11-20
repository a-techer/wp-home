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
if ( !defined( 'WP_HOME_MNGT_VERSION' ) ) {
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
	public $wphmngt_account;
	public $wphmngt_categories;

	/**
	 * Instanciate plugin main controller.
	 * Call the different function allowing to manage the plugin
	 */
	function __construct() {
		/**	Load plugin internationnalisation	*/
		load_plugin_textdomain( 'wp_home_mngt', false, WP_HOME_MNGT_LANGUAGES_DIR);

		/**	Instanciate display component	*/
		parent::__construct();

		/** call plugin menu initialisation */
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );

		/**	call style and javascript librairies	*/
		add_action( 'admin_init', array( &$this, 'backend_style_loader' ) );
		add_action( 'admin_init', array( &$this, 'backend_scripts_loader' ) );
		add_action( 'admin_print_scripts', array( &$this, 'backend_scripts_print' ) );

		/**	Initialise different librairies	*/
		/**	Initialise account librairies	*/
		$this->wphmngt_account = new wphmngt_account();
		/**	Initialise categories	*/
		$this->wphmngt_categories = new wphmngt_categories();

		$this->wphmngt_categories->create_operation_types();
	}

	/**
	 * Create a new item into admin menu for the plugin
	 */
	function admin_menu() {
		add_menu_page( __('Home management dashboard', 'wp_home_mngt'), __('Home', 'wp_home_mngt'), 'manage_options', 'wp-home-dashboard', array( &$this, 'dashboard' ) );
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
			do_meta_boxes( 'wphmngt-dashboard', 'wphmngt-dashboard-left', null);
			do_meta_boxes( 'wphmngt-dashboard', 'wphmngt-dashboard-right', null);

			/**	Fill dashboard template	*/
			$dashboard_content[ 'DASHBOARD' ] = ob_get_contents();
		ob_end_clean();

		$dashboard_content[ 'ACCOUNT_LIST' ] = $this->wphmngt_account->display_account_thumbs();

		echo $this->display( 'dashboard_layout' , $dashboard_content );
	}

	/**
	 * Load css for plugin backend
	 */
	function backend_style_loader() {
		wp_register_style('wphmngt_backend_css', WP_HOME_MNGT_BACKEND_TPL_URL . 'css/backend.css', '', WP_HOME_MNGT_VERSION);
		wp_enqueue_style('wphmngt_backend_css');
	}

	/**
	 * Load scripts for plugin backend
	 */
	function backend_scripts_loader() {
		add_thickbox();

		wp_enqueue_script('jquery-form');
		wp_enqueue_script('wphmngt_backend_js', WP_HOME_MNGT_BACKEND_TPL_URL . 'js/backend.js', '', WP_HOME_MNGT_VERSION);
		wp_enqueue_script('wphmngt_jq_numeric_js', WP_HOME_MNGT_COMMON_TPL_URL . 'js/jquery.numeric.js', '', WP_HOME_MNGT_VERSION);
	}

	/**
	 * Load scripts that will be printed into backend header for global variable usage
	 */
	function backend_scripts_print() {
		require_once( WP_HOME_MNGT_BACKEND_TPL_DIR . 'js/header.js.php' );
	}

	/**
	 * Launch some action when plugin is activated
	 *
	 * @see register_activation_hook
	 */
	function activation() {
		$wphmngt_main_options = get_option( 'wphmngt_options' , array() );
		if ( empty($wphmngt_main_options) || ( empty($wphmngt_main_options['version']) ) ) {
			$this->wphmngt_categories->create_account_types();
			$this->wphmngt_categories->create_operation_types();
			$this->wphmngt_categories->create_payment_method();
			$wphmngt_main_options['version'] = 1;
		}

		update_option( 'wphmngt_options', $wphmngt_main_options);
	}

}

?>
