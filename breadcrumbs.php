<?php
/**
 *	Template part to display breadcrumbs.
 *
 *	@since sequoia 1.0
 */
global  $sequoia_woo_is_active;

$show_breadcrumbs = apply_filters( 'sequoia_options', 'show_breadcrumbs', true );
if( $show_breadcrumbs && !is_home() ) {
	
	$post_type = get_post_type();
		
	if( $sequoia_woo_is_active ) {
		$is_shop = ( is_shop() || is_woocommerce() || is_cart() || is_checkout()) ? true : false ;
	}else{
		$is_shop = false;
	}
	
	if ( $post_type != 'product' && !$is_shop ) {
	
		if (function_exists('dimox_breadcrumbs')  ) {					
			
			dimox_breadcrumbs();
		}
	}else{
	
		do_action('woocommerce_before_main_content'); // to hook woocommerce breadcrumb
	
	}
}

/**
 *  WMPL support:
 */
$lang_sel = isset($of_cypress['lang_sel']) ? $of_cypress['lang_sel'] : null;
if ( function_exists('languages_list') && $lang_sel  ) { 
	languages_list();
}

?>