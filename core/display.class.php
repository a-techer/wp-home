<?php
/**
 * File defining class for plugin display
 *
 * @author Alexandre Techer <alexandre.techer@gmail.com>
 * @version 0.1
 * @package librairies
 * @subpackage core
 */

/** Check if the plugin version is defined. If not defined script will be stopped here */
if ( !defined( 'WP_HOME_MNGT_VERSION' ) ) {
	die( __("You are not allowed to use this service.", 'wp_home_mngt') );
}

/**
 * Display management class
 *
 * @author Alexandre Techer <alexandre.techer@gmail.com>
 * @version 0.1
 * @package librairies
 * @subpackage core
 */
class wphmngt_display {

	/** Define the var that will contains all template elements	*/
	private $tpl_element = array();

	/** Define the var that will contains form utilities	*/
	var $form = null;

	/**
	 * Initialise display utilities.
	 * Get all existing template defined in different part of plugin, and store them for use withoutre-call the definition file each time
	 */
	function __construct() {
		/**	Store all available templates for backend	*/
		if ( is_file(WP_HOME_MNGT_BACKEND_TPL_DIR . 'tpl_elements.tpl.php') ) {
			require( WP_HOME_MNGT_BACKEND_TPL_DIR . 'tpl_elements.tpl.php' );
			$this->set_template_elements( 'backend', $tpl_element );
		}
		/**	Store all available templates for frontend	*/
		if ( is_file(WP_HOME_MNGT_FRONTEND_TPL_DIR . 'tpl_elements.tpl.php') ) {
			require( WP_HOME_MNGT_FRONTEND_TPL_DIR . 'tpl_elements.tpl.php' );
			$this->set_template_elements( 'frontend', $tpl_element );
		}
		/**	Store all available templates for both frontend and backend	*/
		if ( is_file(WP_HOME_MNGT_COMMON_TPL_DIR . 'tpl_elements.tpl.php') ) {
			require( WP_HOME_MNGT_COMMON_TPL_DIR . 'tpl_elements.tpl.php' );
			$this->set_template_elements( 'common', $tpl_element );
		}

		/**	Instanciate a new form 	*/
	//	$this->form = new wp_easy_survey_form( $this );
	}

	/**
	 * Build the private variable containing the different template element for display
	 *
	 * @param string $part The place we are trying to display (front / back / both) Allow to split file for each place
	 * @param array $tpl_to_add The array containing the different templates element to add to internal var
	 */
	function set_template_elements( $part, $tpl_to_add ) {
		$this->tpl_element[ $part ] = $tpl_to_add;
	}

	/**
	 * Retrieve a template part from a given place and a given element to display
	 *
	 * @param string $part The place we are trying to display (front / back / both) Allow to split file for each place
	 * @param unknown_type $tpl_to_get
	 *
	 * @return NULL|string Return null in case no template corresponding to given parameters is found. Return the html output in case template is found
	 */
	function get_template_elements( $part, $tpl_to_get ) {
		$tpl_to_output = null;

		if ( !empty($this->tpl_element[ $part ]) && !empty($this->tpl_element[ $part ][ $tpl_to_get ]) ) {
			$tpl_to_output = $this->tpl_element[ $part ][ $tpl_to_get ];
		}

		return $tpl_to_output;
	}

	/**
	 * Generate output for a given element
	 *
	 * @param string $template_element The template structure to
	 * @param array $template_element_content Available list of component for template feeding
	 *
	 * @return string The template completed with the different element given
	 */
	function display( $template_element_def, $template_element_content, $args = array() ) {
		/** Get the good template element defined by first parameter	*/
		$template_element = $this->check_special_template( $template_element_def, $args );

		/** Read given template parameters for structure completion	*/
		foreach ( $template_element_content as $tpl_component_key => $tpl_component_content) {
			$template_element = str_replace( '{WPHMNGT_TPL_' . $tpl_component_key . '}', $tpl_component_content, $template_element);
		}

		/**	Return output template completly feeded	*/
		return $template_element;
	}

	/**
	 * Check in all existing defined template the template to take for the current element asked to be displayed
	 *
	 * @param string $template_element_def The template identifier to check in existing template
	 * @param array $args_to_check A list of specific arguments to get
	 *
	 * @return mixed The template to take for
	 */
	function check_special_template( $template_element_def, $args ) {
		$template_to_take = null;

		/**	Check if requested element is from backend or frontend	*/
		$frontend_tpl = $this->get_template_elements( 'frontend', $template_element_def );
		$backend_tpl = $this->get_template_elements( 'backend', $template_element_def );
		if ( !is_admin() && !empty( $frontend_tpl ) ) {
			$template_to_take = $this->getValue( $frontend_tpl, $template_element_def, $args);
		}
		else if ( !empty ( $backend_tpl ) ) {
			$template_to_take = $this->getValue( $backend_tpl, $template_element_def, $args);
		}

		/**	If nothing was found in above case, take the default element in common template	*/
		$common_tpl = $this->get_template_elements( 'common', $template_element_def );
		if ( empty($template_to_take) && !empty( $common_tpl ) ) {
			$template_to_take = $this->getValue( $common_tpl , $template_element_def, $args);
		}

		return !empty($template_to_take) ? $template_to_take : sprintf( __('The asked template could not be found in any of existing templates. Check for "%s" template', 'wp_home_mngt'), $template_element_def );
	}

	/**
	 * Check if an array content correspond to given parameters
	 *
	 * @param array|string $lastFound The last specific template founf by the function with given parameters
	 * @param array $array The different parameters to check if specific template exist for
	 * @param array $choosen_template The current template item to check if there is specific template into
	 *
	 * @return boolean
	 */
	function fixArray(&$lastFound, &$array, $choosen_template) {
		$i = 0;
		$isBroken = true;
		while ($isBroken && $i < count($array)) {
			if (array_key_exists($array[$i], $choosen_template)) {
				$isBroken = false;
				$lastFound = $choosen_template[$array[$i]];

				unset($array[$i]);
				$array = array_values($array);
			}
			else {
				$i++;
			}
		}

		return ($isBroken) ? false : true;
	}

	/**
	 * Get a template by checking if there is a specific template from the specified parameters
	 *
	 * @param array|string $complete_template The complete array with all template for the place we are locating in | The specific template already return by previous function
	 * @param array $template_component_to_take The specific template part we are looking for
	 * @param array $array Additionnal parameters allowing to spot a specific template in case many templates exist for a case
	 *
	 * @return string|array The html code for direct output|An array with a list of template
	 */
	function getValue($complete_template, $template_component_to_take, $array = array()) {
		$lastFound = null;
		$mustStop = false;

		$choosen_template = !empty($complete_template) && !empty($complete_template[$template_component_to_take]) ? $complete_template[$template_component_to_take] : $complete_template;

		if ( !empty($array) ) {

			if (array_key_exists($array[0], $choosen_template)) {
				$lastFound = $choosen_template[$array[0]];
				unset($array[0]);
				$array = array_values($array);
			}
			else {
				$isFixed = $this->fixArray($lastFound, $array, $choosen_template);
				$mustStop = ($isFixed ? false : true );
			}

			while (!$mustStop && count($array) > 0) {
				if (is_array($lastFound)) {
					if (array_key_exists($array[0], $lastFound)) {
						$lastFound = $lastFound[$array[0]];
						unset($array[0]);
						$array = array_values($array);
					}
					else {
						$isFixed = $this->fixArray($lastFound, $array, $lastFound);
						$mustStop = ($isFixed ? false : true );
					}
				}
				else {
					$mustStop = true;
				}
			}

		}

		return !empty($lastFound['tpl']) ? $lastFound['tpl'] : ((!empty($choosen_template) && !empty($choosen_template['tpl'])) ? $choosen_template['tpl'] : '');
	}

}

?>