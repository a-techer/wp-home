<?php
/**
 * Categories management librairies
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
 * Categories management librairies
 *
 * @author Alexandre Techer <alexandre.techer@gmail.com>
 * @version 0.1
 * @package wp-home
 * @subpackage modules
 */
class wphmngt_categories extends wphmngt_display {

	/**
	 * Initialise categories management librairies
	 */
	function __construct() {
		/**	Instanciate display component	*/
		parent::__construct();

		/**	Register the different categories for all element	*/
		$this->register_categories();
	}

	/**
	 * Create all categories.
	 *
	 * @see register_taxonomy
	 */
	function register_categories() {
		/**	Create account types	*/
		$labels = array(
			'name'              => _x( 'Account types', 'taxonomy general name', 'wp_home_mngt' ),
			'singular_name'     => _x( 'Account type', 'taxonomy singular name', 'wp_home_mngt' ),
			'search_items'      => __( 'Search account types', 'wp_home_mngt' ),
			'all_items'         => __( 'All account types', 'wp_home_mngt' ),
			'parent_item'       => __( 'Parent Account type', 'wp_home_mngt' ),
			'parent_item_colon' => __( 'Parent Account type:', 'wp_home_mngt' ),
			'edit_item'         => __( 'Edit Account type', 'wp_home_mngt' ),
			'update_item'       => __( 'Update Account type', 'wp_home_mngt' ),
			'add_new_item'      => __( 'Add New Account type', 'wp_home_mngt' ),
			'new_item_name'     => __( 'New Account type', 'wp_home_mngt' ),
			'menu_name'         => __( 'Account type', 'wp_home_mngt' ),
		);
		$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => false,
		);
		register_taxonomy( 'wphmngt_account_types', array( 'wphmngt_account' ), $args );


		/**	Create operation types	*/
		$labels = array(
			'name'              => _x( 'Operation types', 'taxonomy general name', 'wp_home_mngt' ),
			'singular_name'     => _x( 'Operation type', 'taxonomy singular name', 'wp_home_mngt' ),
			'search_items'      => __( 'Search operation types', 'wp_home_mngt' ),
			'all_items'         => __( 'All operation types', 'wp_home_mngt' ),
			'parent_item'       => __( 'Parent Operation type', 'wp_home_mngt' ),
			'parent_item_colon' => __( 'Parent Operation type:', 'wp_home_mngt' ),
			'edit_item'         => __( 'Edit Operation type', 'wp_home_mngt' ),
			'update_item'       => __( 'Update Operation type', 'wp_home_mngt' ),
			'add_new_item'      => __( 'Add New operation type', 'wp_home_mngt' ),
			'new_item_name'     => __( 'New Operation type', 'wp_home_mngt' ),
			'menu_name'         => __( 'Operation type', 'wp_home_mngt' ),
		);
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => false,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => false,
		);
		register_taxonomy( 'wphmngt_operation_types', array( 'wphmngt_account' ), $args );

		/**	Create payment means	*/
		$labels = array(
				'name'              => _x( 'Payment means', 'taxonomy general name', 'wp_home_mngt' ),
				'singular_name'     => _x( 'Payment means', 'taxonomy singular name', 'wp_home_mngt' ),
				'search_items'      => __( 'Search payment means', 'wp_home_mngt' ),
				'all_items'         => __( 'All payment means', 'wp_home_mngt' ),
				'parent_item'       => __( 'Parent payment means', 'wp_home_mngt' ),
				'parent_item_colon' => __( 'Parent payment means:', 'wp_home_mngt' ),
				'edit_item'         => __( 'Edit payment means', 'wp_home_mngt' ),
				'update_item'       => __( 'Update payment means', 'wp_home_mngt' ),
				'add_new_item'      => __( 'Add New payment means', 'wp_home_mngt' ),
				'new_item_name'     => __( 'New payment means', 'wp_home_mngt' ),
				'menu_name'         => __( 'Payment means', 'wp_home_mngt' ),
		);
		$args = array(
				'hierarchical'      => false,
				'labels'            => $labels,
				'show_ui'           => false,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => false,
		);
		register_taxonomy( 'wphmngt_payment_means', array( 'wphmngt_account' ), $args );
	}

	/**
	 *
	 */
	function create_account_types() {
		$account_types = array(
			'current-accounts' => __( 'Current account', 'wp_home_mngt' ),
			'saving-account' => __( 'Saving account', 'wp_home_mngt' ),
		);

		foreach ( $account_types as $slug => $label ) {
			wp_insert_term(
				$label,
				'wphmngt_account_types',
				array(
					'slug' => $slug,
				)
			);
		}
	}

	function create_operation_types() {
		$operation_main_types = array(
			'spending' => __( 'Spending', 'wp_home_mngt' ),
			'receipt' => __( 'Receipt', 'wp_home_mngt' ),
			'saving' => __( 'Saving', 'wp_home_mngt' ),
		);
		foreach ( $operation_main_types as $slug => $label ) {
			wp_insert_term(
				$label,
				'wphmngt_operation_types',
				array(
					'slug' => $slug,
				)
			);
		}

		$operation_sub_types = array(
			'school' => array(
				'label' => __( 'School fees, paper, pens, books', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'canteen' => array(
				'label' => __( 'Canteen', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'food' => array(
				'label' => __( 'Food', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'housekeeping' => array(
				'label' => __( 'Housekeeping', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'home' => array(
				'label' => __( 'Household equipment', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'clothes' => array(
				'label' => __( 'Clothes, shoes', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'beauty' => array(
				'label' => __( 'Beauty', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'medical-expenses' => array(
				'label' => __( 'Medical expenses', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'phone' => array(
				'label' => __( 'Phones (home and cellular)', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'internet' => array(
				'label' => __( 'Internet', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'fuel' => array(
				'label' => __( 'Fuel', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'car-expenses' => array(
				'label' => __( 'Car expenses', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'transport' => array(
				'label' => __( 'Transport', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'cinema' => array(
				'label' => __( 'Cinema', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'oub' => array(
				'label' => __( 'Pub, coffee', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'restaurant' => array(
				'label' => __( 'Restaurant', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'computer' => array(
				'label' => __( 'Computer, office', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'various' => array(
				'label' => __( 'Various', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'bike' => array(
				'label' => __( 'Bike', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'electricity' => array(
				'label' => __( 'Electricity', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'cat' => array(
				'label' => __( 'Cat', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'tools' => array(
				'label' => __( 'Tools', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'gift' => array(
				'label' => __( 'Gift', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'fast-food' => array(
				'label' => __( 'Fast food', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'disney' => array(
				'label' => __( 'Disney', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'gardening' => array(
				'label' => __( 'Gardening', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'bank-charges' => array(
				'label' => __( 'Bank charges', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'rent' => array(
				'label' => __( 'Rent', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'lunch' => array(
				'label' => __( 'Lunch', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'gas' => array(
				'label' => __( 'Gas', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'online-games' => array(
				'label' => __( 'Online games', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'various-gifts' => array(
				'label' => __( 'Various gift', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'taxes' => array(
				'label' => __( 'Taxes', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'health' => array(
				'label' => __( 'Health', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'insurance' => array(
				'label' => __( 'Insurance', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'bank-loan' => array(
				'label' => __( 'Bank loan', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'work' => array(
				'label' => __( 'Work', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'deezer' => array(
				'label' => __( 'Deezer', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'holiday-meal' => array(
				'label' => __( 'Holiday meal', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'holiday-visits' => array(
				'label' => __( 'Holiday visits', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'holiday-transport' => array(
				'label' => __( 'Holiday transport', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'holiday-accommodation' => array(
				'label' => __( 'Holiday accommodation', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'holiday-memories' => array(
				'label' => __( 'Holiday memories', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'dressmaking' => array(
				'label' => __( 'Dressmaking', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'holiday-food' => array(
				'label' => __( 'Holiday food', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'water' => array(
				'label' => __( 'Water', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'pregnancy' => array(
				'label' => __( 'Pregnancy', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
			'baby' => array(
				'label' => __( 'baby', 'wp_home_mngt' ),
				'parent' => 'spending',
			),
		);
		foreach ( $operation_sub_types as $slug => $definition ) {
			$parent_term = term_exists( $definition[ 'parent' ], 'wphmngt_operation_types' );
			wp_insert_term(
				$definition[ 'label' ],
				'wphmngt_operation_types',
				array(
					'slug' => $slug,
    				'parent'=> $parent_term['term_id'],
				)
			);
		}
	}

	function create_payment_method() {
		$operation_main_types = array(
			'credit-card' => __( 'Credit card', 'wp_home_mngt' ),
			'checks' => __( 'Checks', 'wp_home_mngt' ),
			'bank-transfert' => __( 'Receipt', 'wp_home_mngt' ),
			'direct-debit' => __( 'Direct debit', 'wp_home_mngt' ),
		);
		foreach ( $operation_main_types as $slug => $label ) {
			wp_insert_term(
				$label,
				'wphmngt_operation_types',
				array(
					'slug' => $slug,
				)
			);
		}
	}

}

?>