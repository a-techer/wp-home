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
if ( !defined( 'WP_HOME_MNGT_VERSION' ) ) {
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

	private $account_fields = array( 'NAME', 'CURRENT_BALANCE', 'SERIAL_NUMBER' );

	var $post_type = 'wphmngt_account';

	/**
	 * Initialize account management component
	 */
	function __construct() {
		/**	Instanciate display component	*/
		parent::__construct();

		/**	Create the post type for account management	*/
		$this->definition();

		/**	Ajax support	*/
		add_action( 'wp_ajax_ajax-wphmngt-new-account-form-load' , array( &$this, 'display_account_form') );
		add_action( 'wp_ajax_ajax-wphmngt-edit-account-form-load' , array( &$this, 'display_account_form') );
		add_action( 'wp_ajax_ajax-wphmngt-save-account' , array( &$this, 'save_account') );
		add_action( 'wp_ajax_ajax-wphmngt-delete-account' , array( &$this, 'trash_account') );
	}

	/**
	 * Define the post type for element
	 */
	function definition() {
		$labels = array(
			'name' 					=> __( 'Accounts', 'wp_home_mngt' ),
			'singular_name' 		=> __( 'Account', 'wp_home_mngt' ),
			'add_new'				=> _x( 'Add new', 'New account for home management', 'wp_home_mngt' ),
			'add_new_item' 			=> _x( 'Add new account','New account for home management', 'wp_home_mngt' ),
			'edit_item' 			=> __( 'Edit account', 'wp_home_mngt' ),
			'new_item' 				=> __( 'New account', 'wp_home_mngt' ),
			'view_item' 			=> __( 'View account', 'wp_home_mngt' ),
			'search_items' 			=> __( 'Search accounts', 'wp_home_mngt' ),
			'not_found' 			=> __( 'No accounts found', 'wp_home_mngt' ),
			'not_found_in_trash' 	=> __( 'No accounts found in Trash', 'wp_home_mngt' ),
		);

		register_post_type( $this->post_type , array(
			'labels' 			=> $labels,
			'public' 			=> false,
			'show_ui' 			=> true,
			'show_in_menu'		=> false,
			'supports' 			=> array(
				'title',
			),
			'capability_type' 	=> 'post',
			'hierarchical' 		=> false,
			'rewrite' 			=> false,
			'query_var' 		=> $this->post_type,
		));
	}

	/**
	 * Get existing account list into database if account have already been created
	 *
	 * @return WP_Query A wordpress object containing query results
	 */
	function get_account_list() {
		$account_params = array(
			'post_type' => $this->post_type,
			'posts_per_page' => -1,
			'order_by' => 'menu_order',
			'order' => 'ASC',
		);
		$accounts = new WP_Query( $account_params );

		return $accounts;
	}



	/**
	 * Build and return html ouput for area where created account and account creation button will be displayed
	 *
	 * @return string The html output for area containing the different existing account or the new account creation area
	 */
	function display_account_thumbs() {
		$output = '';

		$accounts = $this->get_account_list();
		if ( !empty( $accounts ) && ( $accounts->have_posts() ) ) {
			$account_list = '';
			foreach ( $accounts->posts as $account ) {
				$account_list .= $this->display_account_thumb( $account );
			}
			$output = $this->display( 'dashboard_account_list_container' , array(
				'ACCOUNT_LIST' => $account_list,
			) );
		}
		else {
			$output = $this->display( 'dashboard_account_empty_list', array() );
		}

		return $output;
	}

	/**
	 * Build and return html output for an existing account summary thumb
	 *
	 * @uses get_post_meta()
	 *
	 * @param object $account An object with the basic definition of an account (retrieved into wp_posts database table)
	 *
	 * @return string The html output for an account set by given parameter
	 */
	function display_account_thumb( $account ) {
		$output = '';

		/**	Get current account meta informations	*/
		$more_tpl = array();
		$current_account_infos = get_post_meta( $account->ID, '_wphmngt_account_infos', true );
		foreach ( $current_account_infos as $info_key => $info ) {
			$more_tpl[ 'ACCOUNT_INFOS_' . strtoupper( $info_key) ] = $info;
		}

		/**	Build final output	*/
		$output = $this->display( 'dashboard_account_item', array_merge( array(
			'ACCOUNT_ID' => $account->ID,
			'ACCOUNT_NAME' => $account->post_title,
		), $more_tpl ) );

		return $output;
	}


	/**
	 * Ajax - Create and display the form allowing to create an account
	 */
	function display_account_form() {
		$output = '';

		/**	Define default values for input form	*/
		$account_def = $account_type = null;
		$account_metas = array();
		$button = $this->display( 'account_save_button_creation', array( ));

		/**	Check if there is an account asked for edition	*/
		if ( !empty( $_REQUEST[ 'account_ID' ] ) ) {
			$account_def = get_post( $_REQUEST[ 'account_ID' ] );
			$account_infos = get_post_meta( $_REQUEST[ 'account_ID' ], '_wphmngt_account_infos', true );
			foreach ( $account_infos as $info_key => $info ) {
				$account_metas[ 'ACCOUNT_' . strtoupper( $info_key ) ] = $info;
			}
			$account_type = wp_get_post_terms( $_REQUEST[ 'account_ID' ], 'wphmngt_account_types', array( "fields" => "ids" ) );
			$button = $this->display( 'account_save_button_edition', array(
				'ACCOUNT_ID' => $_REQUEST[ 'account_ID' ],
			));
		}
		else {
			foreach ( $this->account_fields as $field ) {
				$account_metas[ 'ACCOUNT_' . strtoupper( $field ) ] = '';
			}
		}

		/**	Display the account edition / creation form	*/
		$output = $this->display( 'account_form', array_merge( array(
			'CREATE_ACCOUNT_NONCE' => wp_nonce_field( 'wphmngt-save-account', 'wphmngt-nonce' ),

			'ACCOUNT_NAME' => !empty( $account_def ) ? $account_def->post_title : '',

			'ACCOUNT_TYPE_LIST' => wp_dropdown_categories( array(
				'show_option_all'    => '',
				'show_option_none'   => '',
				'orderby'            => 'ID',
				'order'              => 'ASC',
				'show_count'         => false,
				'hide_empty'         => false,
				'child_of'           => false,
				'exclude'            => '',
				'echo'               => false,
				'selected'           => ( !empty( $account_type ) ? $account_type[ 0 ] : '' ),
				'hierarchical'       => false,
				'name'               => 'wphmngt_account[type]',
				'id'                 => 'wphmngt_account_type',
				'class'              => '',
				'depth'              => false,
				'tab_index'          => false,
				'taxonomy'           => 'wphmngt_account_types',
				'hide_if_empty'      => false,
				'walker'             => '',
			) ),

			'SAVE_ACCOUNT_BUTTON' => $button,
		), $account_metas ) );

		echo $output;
		die();
	}


	/**
	 * Ajax - Save a new account into database for allowed users. Checking a nonce
	 */
	function save_account() {
		/**	Define default response status	*/
		$response = array(
			'status' => false,
		);

		/**	Check if requested action is well set	*/
		if (!empty( $_POST['wphmngt-nonce'] ) && wp_verify_nonce( $_POST['wphmngt-nonce'], 'wphmngt-save-account' ) ) {
			$account_id = 0;
			if ( !empty( $_POST[ 'wphmngt_account' ] ) && !empty( $_POST[ 'wphmngt_account' ][ 'posts' ] ) && empty( $_POST[ 'wphmngt_account' ][ 'posts' ][ 'ID' ] ) ) {
				/**	Create the account "post" into database	*/
				$account_id = wp_insert_post( array_merge ( array(
					'post_type' => $this->post_type,
					'post_status' => 'publish',
				), $_POST[ 'wphmngt_account' ][ 'posts' ] ) );

				$success_message = __( 'Account created successfully', 'wp_home_mngt' );
				$error_message = __( 'An error occured while creating account. Please try later.', 'wp_home_mngt' );
			}
			else if ( !empty( $_POST[ 'wphmngt_account' ][ 'posts' ][ 'ID' ] ) ) {
				/**	Update the account	*/
				$account_id = wp_update_post( $_POST[ 'wphmngt_account' ][ 'posts' ] );

				$success_message = __( 'Account saved successfully', 'wp_home_mngt' );
				$error_message = __( 'An error occured while saving account modification. Please try later.', 'wp_home_mngt' );
			}

			/**	If the account is well created the function returns an integer	*/
			if ( is_int( $account_id ) && ( $account_id != 0 ) ) {
				/** Save account type information - Treat sended cat ids to be sure that they are int and single 	*/
				$cat_ids = array_unique( array_map('intval', array( $_POST[ 'wphmngt_account' ][ 'type' ] ) ) );
				wp_set_object_terms( $account_id, $cat_ids, 'wphmngt_account_types', false );

				/**	Save different information about account	*/
				update_post_meta( $account_id, '_wphmngt_account_infos', $_POST[ 'wphmngt_account' ][ 'postmeta' ] );

				$response[ 'status' ] = true;
				$response[ 'message' ] = $success_message;
				$response[ 'output' ] = $this->display_account_thumbs( );
			}
			/**	In case the creation failed, the function return an error object	*/
			else {
				$response[ 'message' ] = $error_message;
			}

			$response[ 'account' ] = $account_id;
		}
		else {
			$response[ 'message' ] = __( 'We are unable to check if you are allowed to use this form. Please contact administrator.', 'wp_home_mngt');
		}

		echo json_encode( $response );
		die();
	}

	/**
	 * Ajax - Set account status to trash.
	 */
	function trash_account() {
		check_admin_referer( 'wphmngt-delete-account', 'wphmngt-nonce' );
		$response = array();

		/**	Update the account status	*/
		$update_result = wp_update_post( array(
			'ID' => $_POST[ 'account' ],
			'post_status' => 'trash',
		) );

		/**	Check update status for return	*/
		if ( !empty( $update_result ) ) {
			$response[ 'status' ] = 'success';
			$response[ 'message' ] = __( 'Account has been deleted successfully', 'wp_home_mngt' );
			$response[ 'output' ] = $this->display_account_thumbs( );
		}
		else {
			$response[ 'status' ] = 'error';
			$response[ 'message' ] = __( 'An error occured while deleting account. Please retry or contact administrator.', 'wp_home_mngt' );
		}
		$response[ 'account' ] = $_POST[ 'account' ];

		echo json_encode( $response );
		die();
	}

}

?>