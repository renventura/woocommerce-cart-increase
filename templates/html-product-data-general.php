<?php
/**
 * HTML for the product settings (Product Data meta box)
 *
 *	@package WooCommerce Cart Increase
 *	@author Ren Ventura
 */
?>

<div class="options_group cart-increase hide_if_external">

	<h4 style="padding-left:12px;"><strong><?php _e( 'Cart Increase', WC_CART_INCREASE_TEXT_DOMAIN ); ?></strong></h4>

	<?php
		woocommerce_wp_checkbox( array(
			'id'            => '_enable_cart_increase',
			'value'         => get_post_meta( $post->ID, '_enable_cart_increase', true ) ? 'yes' : 'no',
			'label'         => __( 'Enable cart increase?', WC_CART_INCREASE_TEXT_DOMAIN ),
			'description'   => __( 'This will increase the cart by the percentage below, or the site default of ' . \RenVentura\WooCommerceCartIncrease\WooCommerce_Cart_Increase::get_default_cart_increase( $post->ID ) . '% if no percentage is given.', WC_CART_INCREASE_TEXT_DOMAIN ),
			'desc_tip'      => true,
		) );
	?>

	<div id="cart_increase_wrapper" class="<?php echo get_post_meta( $post->ID, '_enable_cart_increase', true ) ? '' : 'hidden'; ?>">
		<?php
			woocommerce_wp_text_input( array(
				'id'            => '_cart_increase',
				'value'         => get_post_meta( $post->ID, '_cart_increase', true ),
				'label'         => __( 'Cart Increase %', WC_CART_INCREASE_TEXT_DOMAIN ),
				'desc_tip'      => true,
				'description'   => __( 'Enter a whole number for the cart increase percentage (e.g. 15 = 15%); Site default is ' . \RenVentura\WooCommerceCartIncrease\WooCommerce_Cart_Increase::get_default_cart_increase( $post->ID ) . '%', WC_CART_INCREASE_TEXT_DOMAIN ),
				'type'          => 'number',
				'placeholder'   => \RenVentura\WooCommerceCartIncrease\WooCommerce_Cart_Increase::get_default_cart_increase(),
				'custom_attributes' => array(
					'step'      => 1,
					'min'       => 1,
				)
			) );
		?>
	</div>

	<?php // Hide number input if increase is not enabled ?>
	<script>
		jQuery(document).ready(function($) {
			var cartIncreaseWrapper = $('.cart-increase #cart_increase_wrapper');
			$('input#_enable_cart_increase').change(function() {
				if ( $(this).prop('checked') ) {
					cartIncreaseWrapper.removeClass('hidden');
				} else {
					cartIncreaseWrapper.addClass('hidden');
				}
			});
		});
	</script>
</div>