<?php
/**
 *	Template part to display secondary menu and/or languages selector in header.
 *
 *	@since sequoia 1.0
 */

/**
 *  WMPL support:
 */
global $sequoia_woo_is_active;

$lang_sel = apply_filters( 'sequoia_options', 'lang_sel', true );
$header = apply_filters( 'sequoia_options', 'orientation', 'horizontal' );

if ( function_exists('languages_list') && $lang_sel && $header ) { 
	languages_list();
}
?>

<nav id="secondary-nav">

	<?php 
	$walker = new My_Walker;
	wp_nav_menu( array( 
			'theme_location' => 'secondary',
			//'menu' => 'Main menu',
			'walker' =>$walker,
			'link_before' =>'',
			'link_after' =>'',
			'menu_class' => 'navigation',
			'container' => false 
			) 
		);
	?>
	
</nav>