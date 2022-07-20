<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Sda_Woo_Product_Identifiers
 * @subpackage Sda_Woo_Product_Identifiers/public
 * @author     Spirit Digital Agency <support@spirit.com.gr>
 */
class Sda_Woo_Product_Identifiers_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Get registered fields to handle
	 *
	 * @since       1.0.0
	 * @return      array
	 */
	public function registered_fields() {
		$fields = Sda_Woo_Product_Identifiers::get_fields();
		return apply_filters( 'sda_woocommerce_identifier_register_fields', $fields);
	}

	/**
	 * Adds identifier fields to product JSON-LD.
	 *
	 * @since       1.0.0
	 * @return      array    Modified WooCommerce product structured data.
	 */
	public function add_identifiers_to_structured_data( $markup, $product ) {

		if ( ! $product ) {
			return $markup;
		}

		$product_id     = $product->get_id();
		$markup['gtin-12']  = trim( get_post_meta( $product_id, '_sda_upc', true ) );
		$markup['mpn']  = trim( get_post_meta( $product_id, '_sda_mpn', true ) );
		$markup['isbn'] = trim( get_post_meta( $product_id, '_sda_isbn', true ) );

		return $markup;

	}

	/**
	 * Extend admin search to query identifier fields
	 *
	 * @since       1.0.0
	 * @return      mixed
	 */
	function extend_search( $query ) {
		if( is_admin() ) {
			return;
		}
		$meta_keys   = [];
		foreach ( $this->registered_fields() as $field ) {
			if ( ! isset( $field['searchable'] ) || $field['searchable'] == false) {
				continue;
			}
			$sanitized_key = sanitize_text_field( $field['key'] );
			if ( ! empty( $sanitized_key ) ) {
				$meta_keys[] = $sanitized_key;
			}
		}
		$search_term = $query->query_vars['s'];

		$query->query_vars['s'] = '';

		if ( $search_term != '' ) {
			$meta_query = array( 'relation' => 'OR' );
			foreach( $meta_keys as $meta_key ) {
				array_push( $meta_query, array(
					'key' => $meta_key,
					'value' => $search_term,
					'compare' => 'LIKE'
				));
			}
			$query->set( 'meta_query', $meta_query );
		};
	}

}
