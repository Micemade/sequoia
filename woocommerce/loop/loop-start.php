<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
$anim		= apply_filters( 'sequoia_options', 'prod_hover_anim', 'anim-0' );
$data_anim	= apply_filters( 'sequoia_options', 'prod_data_anim', 'data-anim-01' );
?>
<ul class="products <?php echo ' '.$anim ;?> <?php echo $data_anim == 'none' ? '' : $data_anim; ?>">