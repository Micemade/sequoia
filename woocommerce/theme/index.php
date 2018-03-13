<?php
/**
 * WooCommerce theme functions
 * 
 * @since 1.5.0
 * @package WordPress
 * @subpackage Sequoia
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require get_theme_file_path( 'woocommerce/theme/segments.php' );

require get_theme_file_path( 'woocommerce/theme/images.php' );

require get_theme_file_path( 'woocommerce/theme/numbers.php' );

require get_theme_file_path( 'woocommerce/theme/plugins.php' );

require_once get_theme_file_path( 'woocommerce/single-product/tabs/custom-size-wootab.php' );