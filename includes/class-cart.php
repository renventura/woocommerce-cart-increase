<?php
/**
 *	Handle the cart-total increase by adding a cart fee when an increase needs applied
 *
 *	@package WooCommerce Cart Increase
 *	@author Ren Ventura
 */

namespace RenVentura\WooCommerceCartIncrease\Cart;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cart {

	public function __construct() {

		add_action( 'woocommerce_cart_calculate_fees', array( $this, 'increase_cart_total' ) );
	}

	/**
	 * Increase the customer's cart total
	 *
	 * @param object $cart_object - Cart
	 *
	 * @return void
	 */
	public function increase_cart_total( $cart_object ) {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		
		$increases = array();
		$products = $cart_object->get_cart();
		$subtotal = $cart_object->get_subtotal();

		// Only apply the increase if there is more than one product in the cart
		if ( count( $products ) < 2 ) {
			return;
		}
		
		// Assemble all the percentage increases in the cart
		foreach ( $products as $product ) {
			
			// Bail if product is not configured to increase cart total
			if ( ! get_post_meta( $product['product_id'], '_enable_cart_increase', true ) ) {
				continue;
			}

			$increases[] = \RenVentura\WooCommerceCartIncrease\WooCommerce_Cart_Increase::get_product_cart_increase( $product['product_id'] );
		}

		// Determine correct percentage (maximum), and calculate the increase
		$percent = ( count( $increases ) > 0 ) ? max( $increases ) : 0;
		$percent /= 100;
		$fee = $percent * $subtotal;

		// Add increase to the cart as a fee
		$cart_object->add_fee( __( 'Codeable Surcharge', WC_CART_INCREASE_TEXT_DOMAIN ), $fee );
	}
}

new Cart;