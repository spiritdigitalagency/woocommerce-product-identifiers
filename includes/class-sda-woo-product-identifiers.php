<?php

/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    Sda_Woo_Product_Identifiers
 * @subpackage Sda_Woo_Product_Identifiers/includes
 * @author     Spirit Digital Agency <support@spirit.com.gr>
 */
class Sda_Woo_Product_Identifiers {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Sda_Woo_Product_Identifiers_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SDA_WOO_PRODUCT_IDENTIFIERS_VERSION' ) ) {
			$this->version = SDA_WOO_PRODUCT_IDENTIFIERS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'sda-woo-product-identifiers';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Sda_Woo_Product_Identifiers_Loader. Orchestrates the hooks of the plugin.
	 * - Sda_Woo_Product_Identifiers_i18n. Defines internationalization functionality.
	 * - Sda_Woo_Product_Identifiers_Admin. Defines all hooks for the admin area.
	 * - Sda_Woo_Product_Identifiers_Public. Defines all hooks for the public side of the site.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sda-woo-product-identifiers-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sda-woo-product-identifiers-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sda-woo-product-identifiers-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sda-woo-product-identifiers-public.php';

		$this->loader = new Sda_Woo_Product_Identifiers_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Sda_Woo_Product_Identifiers_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Sda_Woo_Product_Identifiers_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'woocommerce_product_options_inventory_product_data', $plugin_admin, 'product_identifier_fields' );
		$this->loader->add_action( 'woocommerce_product_after_variable_attributes', $plugin_admin, 'variation_identifier_fields', 10, 3 );
		$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'save_product' );
		$this->loader->add_action( 'woocommerce_save_product_variation', $plugin_admin, 'save_product', 10, 2 );
		$this->loader->add_action( 'request', $plugin_admin, 'extend_search', 20);
		$this->loader->add_action( 'woocommerce_csv_product_import_mapping_options', $plugin_admin, 'export_columns');
		$this->loader->add_action( 'woocommerce_csv_product_import_mapping_default_columns', $plugin_admin, 'import_columns');
		$this->loader->add_filter( 'woocommerce_product_import_pre_insert_product_object', $plugin_admin, 'import_data', 10, 2 );
		$this->loader->add_action( 'woocommerce_product_export_column_names', $plugin_admin, 'export_columns');
		$this->loader->add_action( 'woocommerce_product_export_product_default_columns', $plugin_admin, 'export_columns');
		foreach ( self::get_fields() as $field ) {
			$this->loader->add_filter( 'woocommerce_product_export_product_column_' . $field['key'], $plugin_admin, 'export_data', 10, 3 );
		}

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Sda_Woo_Product_Identifiers_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_filter( 'woocommerce_structured_data_product', $plugin_public, 'add_identifiers_to_structured_data', 10, 2 );
		$this->loader->add_filter( 'pre_get_posts', $plugin_public, 'extend_search');

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Sda_Woo_Product_Identifiers_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Returns the identifier fields the plugin should handle
	 *
	 * @return array
	 */
	public static function get_fields() {

		return apply_filters( 'sda_woocommerce_identifier_register_fields', array(
			'upc' => array(
				'key'         => '_sda_upc',
				'label'       => __('UPC', 'sda-woo-product-identifiers'),
				'searchable'  => true,
				'description' => __('Universal Product Code (UPC). Used in primarily North America.', 'sda-woo-product-identifiers')
			),
			'ean' => array(
				'key'         => '_sda_ean',
				'label'       => __('EAN', 'sda-woo-product-identifiers'),
				'searchable'  => true,
				'description' => __('European Article Number (EAN). Used primarily outside of North America.', 'sda-woo-product-identifiers')
			),
			'jan' => array(
				'key'         => '_sda_jan',
				'label'       => __('JAN', 'sda-woo-product-identifiers'),
				'searchable'  => true,
				'description' => __('Japanese Article Number (JAN). Used only in Japan.', 'sda-woo-product-identifiers')
			),
			'isbn' => array(
				'key'         => '_sda_isbn',
				'label'       => __('ISBN', 'sda-woo-product-identifiers'),
				'searchable'  => true,
				'description' => __('International Standard Book Number (ISBN). Used globally.', 'sda-woo-product-identifiers')
			),
			'mpn' => array(
				'key'         => '_sda_mpn',
				'label'       => __('MPN', 'sda-woo-product-identifiers'),
				'searchable'  => true,
				'description' => __('Manufacturer Part Number (MPN). Used globally.', 'sda-woo-product-identifiers')
			),
			'skroutz_feed' => array(
				'key'         => '_sda_skroutz_feed',
				'label'       => __('Skroutz Feed ID', 'sda-woo-product-identifiers'),
				'searchable'  => false,
				'description' => __('Used to maintain backward compatible Skroutz IDs', 'sda-woo-product-identifiers')
			),
		));

	}

}
