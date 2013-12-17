<?php
/**
 * Main plugin configuration file
 *
 * @author Alexandre Techer <alexandre.techer@gmail.com>
 * @version 0.1
 * @package configuration
 */

/** Check if the plugin version is defined. If not defined script will be stopped here	*/
if ( !defined( 'WP_HOME_MNGT_VERSION' ) ) {
	die( __("You are not allowed to use this service.", 'wp_home_mngt') );
}

/** Define core librairies directory */
DEFINE( 'WP_HOME_MNGT_CORELIBS_DIR', WP_PLUGIN_DIR . '/' . WP_HOME_MNGT_DIR . '/core/');

/** Define modules librairies directory */
DEFINE( 'WP_HOME_MNGT_MODULESLIBS_DIR', WP_PLUGIN_DIR . '/' . WP_HOME_MNGT_DIR . '/modules/');

/**	Define internationnalisation directory	*/
DEFINE( 'WP_HOME_MNGT_LANGUAGES_DIR', WP_HOME_MNGT_DIR . '/languages/');

/**	Define the templates directories	*/
DEFINE( 'WP_HOME_MNGT_TEMPLATES_MAIN_DIR', WP_PLUGIN_DIR . '/' . WP_HOME_MNGT_DIR . '/templates/');
DEFINE( 'WP_HOME_MNGT_TEMPLATES_MAIN_URL', WP_PLUGIN_URL . '/' . WP_HOME_MNGT_DIR . '/templates/');
DEFINE( 'WP_HOME_MNGT_BACKEND_TPL_DIR', WP_HOME_MNGT_TEMPLATES_MAIN_DIR. 'backend/');
DEFINE( 'WP_HOME_MNGT_BACKEND_TPL_URL', WP_HOME_MNGT_TEMPLATES_MAIN_URL. 'backend/');
DEFINE( 'WP_HOME_MNGT_FRONTEND_TPL_DIR', WP_HOME_MNGT_TEMPLATES_MAIN_DIR . 'frontend/');
DEFINE( 'WP_HOME_MNGT_FRONTEND_TPL_URL', WP_HOME_MNGT_TEMPLATES_MAIN_URL . 'frontend/');
DEFINE( 'WP_HOME_MNGT_COMMON_TPL_DIR', WP_HOME_MNGT_TEMPLATES_MAIN_DIR . 'common/');
DEFINE( 'WP_HOME_MNGT_COMMON_TPL_URL', WP_HOME_MNGT_TEMPLATES_MAIN_URL . 'common/');

/**	Define debug vars	*/
$default_options_definition = array();
$default_options_definition['wphmgt_debug_mode'] = true;
$default_options_definition['wphmgt_debug_allowed_ip'] = array( '127.0.0.1', );
/**	Check if there are options corresponding to this debug vars	*/
$wpee_extra_options = get_option('wphmgt_extra_options', $default_options_definition);
/**	Define all var as global for use in all plugin	*/
foreach ( $wpee_extra_options as $options_key => $options_value ) {
	if ( is_array($options_value) ) {
		$options_value= serialize($options_value);
	}
	DEFINE( strtoupper($options_key), $options_value );
}