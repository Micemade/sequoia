<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

$class = $class . " wc-loop-button";

// 3.0.0 < Fallback conditional :
$product_id	= apply_filters( 'sequoia_wc_version', '3.0.0'  ) ? $product->get_id() : $product->id;

echo apply_filters( 'woocommerce_loop_add_to_cart_link',
	sprintf( '<div class="add-to-cart-holder"><a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="tip-top %s" title="%s" data-tooltip><span class="icon-cart-2"></span></a></div>',
		esc_url( $product->add_to_cart_url() ), // href
		esc_attr( isset( $quantity ) ? $quantity : 1 ), // data-quantity
		esc_attr( $product_id	 ), // data-product_id
		esc_attr( $product->get_sku() ), // data-product_sku
		esc_attr( isset( $class ) ? $class : '' ), // class
		esc_html( $product->add_to_cart_text() ) // title
	),
$product );