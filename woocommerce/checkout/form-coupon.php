<?php
/**
 * Checkout coupon form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

if ( ! WC()->cart->coupons_enabled() ) {
	return;
}

$info_message = apply_filters( 'woocommerce_checkout_coupon_message', __( 'Have a coupon?', 'sequoia' ) . ' <a href="#" class="showcoupon">' . __( 'Click here to enter your code', 'sequoia' ) . '</a>' );
wc_print_notice( $info_message, 'notice' );
?>

<form class="checkout_coupon" method="post" style="display:none">

	<p class="coupon_form">
		<input type="text" name="coupon_code" class="input-text" placeholder="<?php _e( 'Coupon code', 'sequoia' ); ?>" id="coupon_code" value="" />
	</p>

	<p class="coupon_form">
		<input type="submit" class="button tiny" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'sequoia' ); ?>" />
	</p>

	<div class="clear"></div>
</form>