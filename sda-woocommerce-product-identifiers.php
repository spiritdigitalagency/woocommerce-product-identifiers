<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://spirit.com.gr
 * @since             1.0.0
 * @package           Sda_Woo_Product_Identifiers
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Product Identifier Fields (UPC, EAN, JAN, ISBN, and MPN)
 * Plugin URI:        https://github.com/spiritdigitalagency/
 * Description:       Add GTINs (Global Trade Item Numbers) including UPC, EAN, JAN, ISBN, and MPN fields to your WooCommerce products.
 * Version:           1.0.0
 * Author:            Spirit Digital Agency
 * Author URI:        https://spirit.com.gr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sda-woo-product-identifiers
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Currently plugin version.
 */
define( 'SDA_WOO_PI_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sda-woo-product-identifiers-activator.php
 */
function activate_sda_woo_product_identifiers() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sda-woo-product-identifiers-activator.php';
	Sda_Woo_Product_Identifiers_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sda-woo-product-identifiers-deactivator.php
 */
function deactivate_sda_woo_product_identifiers() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sda-woo-product-identifiers-deactivator.php';
	Sda_Woo_Product_Identifiers_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sda_woo_product_identifiers' );
register_deactivation_hook( __FILE__, 'deactivate_sda_woo_product_identifiers' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sda-woo-product-identifiers.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_sda_woo_product_identifiers() {

	$plugin = new Sda_Woo_Product_Identifiers();
	$plugin->run();

}
run_sda_woo_product_identifiers();
