<?php
/**
 *	Add settings to the WooCommerce settings page
 *
 *	@package WooCommerce Cart Increase
 *	@author Ren Ventura
 */

namespace RenVentura\WooCommerceCartIncrease\Settings;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings {
	
	public function __construct() {

		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'wc_product_data_general_tab' ), 99 );
		add_filter( 'woocommerce_products_general_settings', array( $this, 'wc_settings_product_general_section' ) );

		add_action( 'woocommerce_process_product_meta', array( $this , 'save_product_meta' ) );
	}

	/**
	 * Output tab markup (General tab)
	 *
	 * @return void
	 */
	public function wc_product_data_general_tab() {

		global $post;

		include_once WC_CART_INCREASE_PLUGIN_DIR_PATH . 'templates/html-product-data-general.php';
	}

	/**
	 * Add a global setting to the WooCommerce settings -> Products -> General tab
	 *
	 * @param array $settings - WooCommerce settings, Products tab, General section
	 *
	 * @return array - New settings
	 */
	public function wc_settings_product_general_section( $settings ) {

		$settings[] = array(
			'title'    => __( 'Cart Increase', WC_CART_INCREASE_TEXT_DOMAIN ),
			'type'     => 'title',
			'desc'     => __( 'Global settings for cart increases.', WC_CART_INCREASE_TEXT_DOMAIN ),
			'id'       => 'cart_increase_title',
		);
		
		$settings[] = array(
			'title'    => __( 'Default Increase', WC_CART_INCREASE_TEXT_DOMAIN ),
			'desc'     => __( 'When a product is configured to add a cart increase, but an increase % is not set for that product, this will be the percentage.', WC_CART_INCREASE_TEXT_DOMAIN ),
			'id'       => 'woocommerce_cart_increase',
			'default'  => '10',
			'type'     => 'number',
			'desc_tip' => true,
			'css'      => 'width:100px;',
			'custom_attributes' => array(
				'min'  => 1,
				'step' => 1,
			)
		);

		$settings[] = array(
			'type' 	=> 'sectionend',
			'id' 	=> 'cart_increase_end',
		);

		return $settings;
	}

	/**
	 * Update product meta when saved
	 *
	 * @param int $post_id - ID of the product being saved
	 *
	 * @return void
	 */
	public function save_product_meta( $post_id ) {

		// Enable setting
		if ( isset( $_POST['_enable_cart_increase'] ) && $_POST['_enable_cart_increase'] === 'yes' ) {
			update_post_meta( $post_id, '_enable_cart_increase', true );
		} else {
			delete_post_meta( $post_id, '_enable_cart_increase' );
		}

		// Increase percentage input
		if ( isset( $_POST['_cart_increase'] ) && (int) $_POST['_cart_increase'] > 0 ) {
			update_post_meta( $post_id, '_cart_increase', (int) sanitize_text_field( $_POST['_cart_increase'] ) );
		} else {
			delete_post_meta( $post_id, '_cart_increase' );
		}
	}
}

new Settings;