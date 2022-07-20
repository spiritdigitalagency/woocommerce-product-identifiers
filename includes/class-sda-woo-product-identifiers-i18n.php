<?php

/**
 * Define the internationalization functionality.
 *
 * @since      1.0.0
 * @package    Sda_Woo_Product_Identifiers
 * @subpackage Sda_Woo_Product_Identifiers/includes
 * @author     Spirit Digital Agency <support@spirit.com.gr>
 */
class Sda_Woo_Product_Identifiers_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'sda-woo-product-identifiers',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

}
