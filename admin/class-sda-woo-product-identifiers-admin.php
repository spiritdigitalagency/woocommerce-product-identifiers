<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Sda_Woo_Product_Identifiers
 * @subpackage Sda_Woo_Product_Identifiers/admin
 * @author     Spirit Digital Agency <support@spirit.com.gr>
 */
class Sda_Woo_Product_Identifiers_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Add identifier fields for product
	 *
	 * @since       1.0.0
	 * @return      void
	 */
	public function product_identifier_fields() {

		$product = wc_get_product();
		if ( ! empty( $product ) && $product->is_type( 'variable' ) ) {
			return;
		}

		$this->display_fields( $product->get_id() );
	}

	/**
	 * Add identifier fields for variations
	 *
	 * @since       1.0.0
	 * @return      void
	 */
	public function variation_identifier_fields( $loop, $variation_data, $variation ) {
		$this->display_fields( $variation->ID );
	}

	/**
	 * Generate identifiers fields html code for a product
	 *
	 * @since       1.0.0
	 * @return      void
	 */
	protected function display_fields( $post_id ) {

		foreach ( Sda_Woo_Product_Identifiers::get_fields() as $field ) {
			woocommerce_wp_text_input(
				array(
					'id'          => $field['key'] . '[' . $post_id . ']',
					'label'       => $field['label'],
					'desc_tip'    => empty( $field['description'] ) ? 'true' : 'false',
					'description' => ! empty( $field['description'] ) ? $field['description'] : '',
					'value'       => get_post_meta( $post_id, $field['key'], true ),
				)
			);
		}

	}

	/**
	 * Saves a product meta field
	 *
	 * @since       1.0.0
	 * @return      void
	 */
	protected function save_field( $post_id, $key, $data = null ) {

		if ( ! isset( $data ) ) {
			return;
		}

		if ( ! is_array( $data ) || ! array_key_exists( $post_id, $data ) ) {
			return;
		}

		update_post_meta( $post_id, $key, esc_attr( $data[ $post_id ] ) );

		if ( empty( get_post_meta( $post_id, $key, true ) ) ) {
			delete_post_meta( $post_id, $key, '' );
		}
	}

	/**
	 * Save simple product identifier fields
	 *
	 * @since       1.0.0
	 * @return      void
	 */
	public function save_product( $post_id ) {

		foreach ( Sda_Woo_Product_Identifiers::get_fields() as $field ) {
			$field_data = $_POST[ $field['key'] ];
			if ( ! isset( $field_data ) ) {
				continue;
			}
			$this->save_field( $post_id, $field['key'], $field_data );
		}

	}

	/**
	 * Prepare columns for csv export file
	 *
	 * @since       1.0.0
	 * @return      array
	 */
	public function export_columns( $columns ) {

		foreach ( Sda_Woo_Product_Identifiers::get_fields() as $field ) {
			$columns[$field['key']] = $field['label'];
		}

		return $columns;

	}

	/**
	 * Prepare data for extra columns
	 *
	 * @since       1.0.0
	 * @return      array
	 */
	public function export_data( $value, $product, $column_id ) {
		return $product->get_meta( $column_id, true, 'edit' );
	}

	/**
	 * Prepare columns for csv import file
	 *
	 * @since       1.0.0
	 * @return      array
	 */
	public function import_columns( $columns ) {

		foreach ( Sda_Woo_Product_Identifiers::get_fields() as $field ) {
			$columns[$field['label']] = $field['key'];
		}

		return $columns;

	}

	/**
	 * Prepare data for extra columns
	 *
	 * @since       1.0.0
	 * @return      array
	 */
	public function import_data( $product, $data ) {

		if ( is_a( $product, 'WC_Product' ) ) {

			foreach ( Sda_Woo_Product_Identifiers::get_fields() as $field ) {
				if ( array_key_exists($field['key'], $data) && ! empty( $data[$field['key']] ) ) {
					$product->update_meta_data( $field['key'], $data[$field['key']] );
				}
			}

		}

		return $product;
	}

	/**
	 * Extend admin search to query identifier fields
	 *
	 * @since       1.0.0
	 * @return      mixed
	 */
	public function extend_search( $query_vars ) {

		if ( ! is_admin() || !isset( $_GET['s'] ) ) {
			return $query_vars;
		}
		global $typenow;
		global $pagenow;
		if('product' !== $typenow || 'edit.php' !== $pagenow || empty($_GET['s']) ) {
			return $query_vars;
		}
		global $wpdb;

		$search_term = esc_sql( sanitize_text_field( $_GET['s'] ) );
		$meta_keys   = [];
		foreach ( Sda_Woo_Product_Identifiers::get_fields() as $field ) {
			if ( ! isset( $field['searchable'] ) || $field['searchable'] == false) {
				continue;
			}
			$sanitized_key = esc_sql( sanitize_text_field( $field['key'] ) );
			if ( ! empty( $sanitized_key ) ) {
				$meta_keys[] = $sanitized_key;
			}
		}
		if ( empty( $meta_keys ) ) {
			return $query_vars;
		}
		$post_types             = array( 'product', 'product_variation' );
		$search_results         = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT DISTINCT posts.ID as product_id, posts.post_parent as parent_id FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->postmeta} AS postmeta ON posts.ID = postmeta.post_id WHERE postmeta.meta_key IN ('" . implode( "','", $meta_keys ) . "') AND postmeta.meta_value LIKE %s AND posts.post_type IN ('" . implode( "','", $post_types ) . "') ORDER BY posts.post_parent ASC, posts.post_title ASC",
				'%' . $wpdb->esc_like( $search_term ) . '%'
			)
		);
		$product_ids            = wp_parse_id_list( array_merge( wp_list_pluck( $search_results, 'product_id' ), wp_list_pluck( $search_results, 'parent_id' ) ) );
		$query_vars['post__in'] = array_merge( $product_ids, $query_vars['post__in'] );

		return $query_vars;

	}

}
