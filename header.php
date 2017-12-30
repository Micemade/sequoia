<!DOCTYPE html>
<?php 
//  GLOBAL OPTIONS DATA
global $sequoia_woo_is_active;
//
// THEME DEMO VARIABLES:
if( apply_filters( 'sequoia_options', 'demo_mode', false ) ) { // because of theme update, remove isset for future
	require_once( get_template_directory() . '/theme_demo_vars.php');
}
?>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->

<!--[if !(IE 8) | !(IE 9)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>

<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,IE=10, chrome=1"><![endif]-->

<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">


<?php wp_head(); ?>

</head>

<body <?php body_class();?> id="body">

<div id="bodywrap">
<?php 
/* #bodywrap is used to fix foundation tooltips 
 * div is referenced in as_custom.js for vertical layout - sub and mega menus position
 */
?>

<?php 
##  IF SIMPLE HORIZONTAL HEADER IS SELECTED
$orientation = apply_filters( 'sequoia_options', 'orientation', 'horizontal' );
$predefined = apply_filters( 'sequoia_options', 'predefined_headers', '01' );

if( $orientation == 'horizontal' && $predefined == 'simple' ) {?>
<div id="st-container" class="st-container">
<?php } ?>


<?php if( apply_filters( 'sequoia_options', 'use_preloader', false ) ) { ?>
<div id="dvLoading"></div>
<?php } ?>

<?php
if( apply_filters( 'sequoia_options', 'demo_mode', false ) ) { // because of theme update, remove isset for future
	get_template_part('theme_demo_switcher');
}
?>

	<?php
	
	/**
	 *	HEADER AND MENU ORIENTATION:
	 */
	
	if( $orientation == 'horizontal' ){
				
		get_template_part('header','horizontal_'.$predefined );
		
		$page_layout = ' horizontal';
		
	}elseif( $orientation == 'vertical' ) {
	
		get_template_part('header','vertical');
		
		$page_layout = ' vertical';
	}	
	
	/**
	 *	SETTINGS FOR FIXED HEADER OPTION
	 */
	global $post;
	
	if( $post ) {
	
		$page_under_head = get_post_meta( $post->ID,'as_page_under_head' );
		$page_under_head_class	= ( $page_under_head && is_singular() ) ? ' page-under-head' : '';
		
	}else{
		$page_under_head_class = '';
	}
	?>
	
	
	<div id="page" class="page<?php echo $page_layout; echo $page_under_head_class; ?>">
	
	<?php if( $orientation == 'vertical' ) { ?>
	<div class="row">
	
		<?php $if_sec_menu = has_nav_menu( 'secondary' ) ? '6' : '12' ?>
		
		<div class="small-<?php echo $if_sec_menu; ?> column breadcrumbs-holder">
		
			<?php
			$lang_sel = apply_filters( 'sequoia_options', 'lang_sel', true );
			if ( function_exists('languages_list') && $lang_sel  ) { 
				languages_list();
			}
			?>
			
			<?php get_template_part('breadcrumbs'); ?>
		
		</div>
		
		<div class="small-6 column" style="float: right !important;">
		
		<?php		
		if ( has_nav_menu( 'secondary' ) ) { 
			get_template_part('secondary_menu');
		}
		?>
		</div>
		
	</div>
	<?php } ?>