<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://takamoastudio.com/
 * @since      1.0.0
 *
 * @package    Ep-sous-domaine-plugin
 * @subpackage Ep-sous-domaine-plugin/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ep-sous-domaine-plugin
 * @subpackage Ep-sous-domaine-plugin/includes
 * @author     Takamoa Studio <responsable@takamoastudio.com
 */

class Ep_sous_domaine_i18n{
    /**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	*/
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ep_sous_domaine.pot',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
        
}