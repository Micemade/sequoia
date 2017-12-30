<!DOCTYPE html>
<?php 
//  GLOBAL OPTIONS DATA
global $sequoia_woo_is_active;
//
// THEME DEMO VARIABLES:
if( apply_filters( 'sequoia_options', 'demo_mode', false ) ) { // because of theme update, remove isset for future
	
	require_once( get_template_directory() . '/theme_demo_vars.php');
	
}
//
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