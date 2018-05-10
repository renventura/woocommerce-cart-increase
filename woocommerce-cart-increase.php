<?php
/*
 * Plugin Name: WooCommerce Cart Increase
 * Plugin URI: #
 * Description: Adds a configurable percentage increase to the WooCommerce cart (Codeable application project)
 * Author: Ren Ventura
 * Author URI: https://renventura.com
 * Version: 1.0
 * Text Domain: 'woocommerce-cart-increase'
 * WC tested up to: 3.3
 *
 * License: GPL 2.0+
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 */

 /*
	Copyright 2018  Ren Ventura <rv@renventura.com>

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	Permission is hereby granted, free of charge, to any person obtaining a copy of this
	software and associated documentation files (the "Software"), to deal in the Software
	without restriction, including without limitation the rights to use, copy, modify, merge,
	publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons
	to whom the Software is furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in all copies or
	substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

namespace RenVentura\WooCommerceCartIncrease;

//* Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce_Cart_Increase' ) ) :

class WooCommerce_Cart_Increase {

	private static $instance;

	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WooCommerce_Cart_Increase ) ) {
			
			self::$instance = new WooCommerce_Cart_Increase;

			self::$instance->constants();
			self::$instance->includes();
			self::$instance->hooks();
		}

		return self::$instance;
	}

	/**
	 * Constants
	 */
	public function constants() {

		// Plugin version
		if ( ! defined( 'WC_CART_INCREASE_VERSION' ) ) {
			define( 'WC_CART_INCREASE_VERSION', '1.0.0' );
		}

		// Text domain
		if ( ! defined( 'WC_CART_INCREASE_TEXT_DOMAIN' ) ) {
			define( 'WC_CART_INCREASE_TEXT_DOMAIN', 'woocommerce-cart-increase' );
		}

		// Plugin file
		if ( ! defined( 'WC_CART_INCREASE_PLUGIN_FILE' ) ) {
			define( 'WC_CART_INCREASE_PLUGIN_FILE', __FILE__ );
		}

		// Plugin basename
		if ( ! defined( 'WC_CART_INCREASE_PLUGIN_BASENAME' ) ) {
			define( 'WC_CART_INCREASE_PLUGIN_BASENAME', plugin_basename( WC_CART_INCREASE_PLUGIN_FILE ) );
		}

		// Plugin directory path
		if ( ! defined( 'WC_CART_INCREASE_PLUGIN_DIR_PATH' ) ) {
			define( 'WC_CART_INCREASE_PLUGIN_DIR_PATH', trailingslashit( plugin_dir_path( WC_CART_INCREASE_PLUGIN_FILE )  ) );
		}

		// Plugin directory URL
		if ( ! defined( 'WC_CART_INCREASE_PLUGIN_DIR_URL' ) ) {
			define( 'WC_CART_INCREASE_PLUGIN_DIR_URL', trailingslashit( plugin_dir_url( WC_CART_INCREASE_PLUGIN_FILE )  ) );
		}
	}

	/**
	 * Include PHP files
	 */
	public function includes() {
		
		include_once 'includes/class-settings.php';
		include_once 'includes/class-cart.php';
	}

	/**
	 * Action/filter hooks
	 */
	public function hooks() {

		register_activation_hook( WC_CART_INCREASE_PLUGIN_FILE, array( $this, 'activate' ) );
		add_filter( 'plugin_action_links_' . WC_CART_INCREASE_PLUGIN_BASENAME, array( $this, 'action_links' ) );
	}

	/**
	 * Run some initial actions on plugin activation
	 *
	 * @return void
	 */
	public function activate() {

		// Deactivate and die if WooCommerce is not active
		if ( ! class_exists( 'WooCommerce' ) ) {
			deactivate_plugins( WC_CART_INCREASE_PLUGIN_BASENAME );
			wp_die( __( 'The WooCommerce Cart Increase plugin requires you to install and activate WooCommerce first.', WC_CART_INCREASE_TEXT_DOMAIN ) );
		}
	}

	/**
	 * Plugin action links
	 *
	 * @param array $links - Plugin action links
	 *
	 * @return array Filtered action links
	 */
	public function action_links( $links ) {

		$settings_url = add_query_arg( array(
			'page' => 'wc-settings',
			'tab' => 'products'
		), admin_url( 'admin.php' ) );

		$action_links = array(
			'settings' => sprintf( '<a href="%1$s" aria-label="%2$s">%2$s</a>', $settings_url, __( 'Cart Increase Settings', WC_CART_INCREASE_TEXT_DOMAIN ) )
		);

		return array_merge( $action_links, $links );
	}

	/**
	 * Get site default cart increase (default to 10%)
	 *
	 * @return int - Default cart increase for products configured to add an increase, but with not increase set
	 */
	public static function get_default_cart_increase() {
		$default = get_option( 'woocommerce_cart_increase' );
		return $default ? (int) $default : 10;
	}

	/**
	 * Get cart increase
	 *
	 * @param int $product_id - ID of the WooCommerce product
	 * 
	 * @return mixed - False if product is not configured to add a cart increase; Else percentage of cart increase, expressed as a whole number
	 */
	public static function get_product_cart_increase( int $product_id ) {

		// Bail if product is not configured to add a cart increase
		if ( ! get_post_meta( $product_id, '_enable_cart_increase', true ) ) {
			return false;
		}

		// Get the cart increase for this product
		$product_increase_percentage = (int) get_post_meta( $product_id, '_cart_increase', true );

		return $product_increase_percentage ? $product_increase_percentage : self::get_default_cart_increase();
	}
}

endif;

/**
 * Main function
 * 
 * @return object - WooCommerce_Cart_Increase instance
 */
function WooCommerce_Cart_Increase() {
	return WooCommerce_Cart_Increase::instance();
}

WooCommerce_Cart_Increase();
