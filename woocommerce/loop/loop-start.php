<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
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
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$anim              = apply_filters( 'sequoia_options', 'prod_hover_anim', 'anim-0' );
$data_anim         = apply_filters( 'sequoia_options', 'prod_data_anim', 'data-anim-01' );
$loop_prop_columns = apply_filters( 'sequoia_wc_version', '3.3.0' ) ? wc_get_loop_prop( 'columns' ) : '';
?>

<ul class="products <?php echo esc_attr( $anim . ' ' . $loop_prop_columns . ' ' . ( 'none' === $data_anim ? '' : $data_anim ) ); ?>">
