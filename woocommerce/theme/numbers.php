<?php
/**
 * WooCommerce numbers (items in catalog, columns, related, up/cross sales ...)
 *
 * @since 1.5.0
 * @package WordPress
 * @subpackage Sequoia
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * NUMBER OF PRODUCTS ON PRODUCTS PAGE:
 *
 */
add_filter( 'loop_shop_per_page', 'products_per_page' );
if ( ! function_exists( 'products_per_page' ) ) {
	function products_per_page () {

		$products_page_settings = apply_filters( 'sequoia_options', 'products_page_settings', array('Products per page'=> 6) );
		$products_number = ! empty( $products_page_settings['Products per page'] ) ? $products_page_settings['Products per page'] : 6;
		return $products_number;
	}
}

/**
 * NUMBER OF COLUMNS IN PRODUCTS AND PROD. TAXNOMIES PAGE
 *
 * @return $columns
 */
function sequoia_loop_columns() {

	$products_page_settings = apply_filters( 'sequoia_options', 'products_page_settings', array( 'Products columns'=> 4 ) );
	$columns =  !empty( $products_page_settings['Products columns'] ) ? $products_page_settings['Products columns'] : 4;

	return $columns;
}
add_filter( 'loop_shop_columns', 'sequoia_loop_columns' );

/**
 * NUMBERS FOR RELATED PRODUCTS.
 *
 * @return void
 */
function sequoia_output_related_products() {

	global $sequoia_wc_version;

	$products_page_settings = apply_filters( 'sequoia_options', 'products_page_settings', array('Related total'=> 4, 'Related columns' => 4) );

	$related_total   =  $products_page_settings['Related total'] ? $products_page_settings['Related total'] : 4;
	$related_columns =  $products_page_settings['Related columns'] ? $products_page_settings['Related columns'] : 4;

	$args = array(
		'posts_per_page' => $related_total,
		'columns' => $related_columns,
		'orderby' => 'rand',
	);
	woocommerce_related_products( apply_filters( 'woocommerce_output_related_products_args', $args ) );

}
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
add_action( 'woocommerce_after_single_product_summary', 'sequoia_output_related_products', 20 );

/**
 * NUMBERS FOR UPSELL PRODUCTS.
 *
 * @return void
 */
function woocommerce_output_upsells() {

	$total  = apply_filters( 'sequoia_options', 'upsell_total', 3 );
	$in_row = apply_filters( 'sequoia_options', 'upsell_in_row', 3 );

	woocommerce_upsell_display( $total, $in_row );
}
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_upsells', 15 );
/**
 * NUMBERS FOR CROSS SELL PRODUCTS
 *
 * @param int $columns
 * @return $cs_columns
 */
function sequoia_cross_sells_columns( $columns ) {

	$seq_cs_columns = apply_filters( 'sequoia_options', 'cross_sell_in_row', '' );
	$cs_columns     = $seq_cs_columns ? $seq_cs_columns : 3;
	return $cs_columns;
}
add_filter( 'woocommerce_cross_sells_columns', 'sequoia_cross_sells_columns' );
/**
 * LIMIT FOR VARIATIONS BEFORE AJAX
 *
 * @param int $qty - interger for number of products
 * @param object $product - product query object
 * @return int
 */
function as_wc_ajax_variation_threshold( $qty, $product ) {
	return 70;
}
add_filter( 'woocommerce_ajax_variation_threshold', 'as_wc_ajax_variation_threshold', 10, 2 );