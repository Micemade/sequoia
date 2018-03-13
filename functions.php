<?php
/**
 *	The MAIN FUNCTIONS FILE - includes all the neccesary additional theme functions, classes etc.
 *
 *	@since sequoia 1.0
 */
/*
 *	OPTIONS FRAMEWORK - INCLUDED FROM "ADMIN" FOLDER
 *
 *
 **/
// Paths to admin functions :
// if NOT CHILD THEME -use the paths and dir bellow:
if ( !is_child_theme() ) {
	define( 'ADMIN_PATH', get_stylesheet_directory() . '/admin/' );
	define( 'ADMIN_DIR', get_template_directory_uri() . '/admin/' );
	define( 'LAYOUT_PATH', ADMIN_PATH . '/layouts/' );
}

$theme_data	= wp_get_theme();
$theme_slug	= sanitize_title( $theme_data );
define( 'THEMENAME', $theme_data );

// Name of the database row in wp_options table where your options are stored
define( 'OPTIONS', 'of_'.$theme_slug ); 
//
// Build Options
require_once ADMIN_PATH . 'admin-interface.php'; // Admin Interfaces.
require_once ADMIN_PATH . 'theme-options.php'; // Options panel settings and custom.settings
require_once ADMIN_PATH . 'admin-functions.php';// Theme actions based on options.settings
// end Options Framework.

/**
 * HTTP or HTTPS protocol
 */
if ( is_ssl() ) {
	$as_protocol = "https";
}else{
	$as_protocol = "http";
}
#
/**
 *	MAIN INITIALIZATIONS:
 *
 */
if ( ! function_exists( 'sequoia_setup' ) ):
	function sequoia_setup() {
		// MAX MEDIA WIDTH.
		if ( ! isset( $content_width ) ) $content_width = 1400;
		// TRANSLATIONS.
		load_theme_textdomain( 'sequoia', get_template_directory() . '/languages' );
		// HTML TITLE META TAG.
		add_theme_support( 'title-tag' );
		// FEEDS.
		add_theme_support( 'automatic-feed-links' );
		// POST FORMATS.
		add_theme_support( 'post-formats', array( 'audio', 'video', 'gallery','image', 'quote' ) );
		//	POST THUMBNAIL SUPPORT.
		add_theme_support( 'post-thumbnails', array( 'post', 'page', 'product', 'portfolio','slide' ) );
		// Add support for WooCommerce.
		add_theme_support( 'woocommerce' );
		// MENUS.
		add_theme_support( 'menus' );
		register_nav_menu( 'main-horizontal', 'Main horizontal menu' );
		register_nav_menu( 'main-vertical', 'Main vertical menu' );
		register_nav_menu( 'main-mobile', 'Main mobile menu' );
		register_nav_menu( 'secondary', 'Secondary menu' );
		// IMAGE RESIZING SCRIPT.
		if( ! function_exists( 'bfi_thumb' ) ) {
			require_once( trailingslashit( get_template_directory() ) . 'inc/functions/BFI_Thumb.php');	
		}
		// IMAGE SIZES (AS = Aligator Studio).
		// - custom portrait and landscape formats.
		add_image_size( 'as-portrait', 500, 700, true );
		add_image_size( 'as-landscape', 1200 ,680, true );
		add_filter( 'image_size_names_choose', 'as_image_sizes_mediapopup', 11, 1 );
		// ENABLE SHORTCODES ON REGULAR TEXT WIDGET.
		add_filter( 'widget_text', 'do_shortcode' ); // te enable shortcodes in widgets
		//
		add_editor_style();
		//
		//
		// THEME WIDGETS
		include( trailingslashit( get_template_directory() ) . 'inc/widgets/widget_latest_images.php' );
		include( trailingslashit( get_template_directory() ) . 'inc/widgets/widget_featured_images.php' );
		include( trailingslashit( get_template_directory() ) . 'inc/widgets/widget_social.php' );
		include( trailingslashit( get_template_directory() ) . 'inc/widgets/latest-custom-posts.php' );
		//
		//
		// CUSTOM META BOXES
		require_once( trailingslashit( get_template_directory() ) . 'inc/Custom-Meta-Boxes/custom-meta-boxes.php' );
		require_once( trailingslashit( get_template_directory() ) . 'inc/functions/as-meta-boxes.php' );
		//
		//
		add_filter( 'wp_calculate_image_srcset_meta', '__return_null' );
	}
endif;
add_action( 'after_setup_theme', 'sequoia_setup' );
/**
 * SEQUOIA OPTIONS 
 *
 * @param [in] $setting - setting ID to fetch from options
 * @param [in] $default - default (fallback) value for setting
 * @return settion option calue
 *
 */
function sequoia_options_func( $setting, $default ) {
	global $of_sequoia;

	if ( is_ssl() ) {
		$of_sequoia =  str_replace( "http://", "https://", $of_sequoia );
	}

	$single_setting = isset( $of_sequoia[ $setting ] ) ? $of_sequoia[ $setting ] : '';

	if ( $single_setting && !empty( $single_setting ) ) {
		$option = $single_setting;
	} else {
		$option = $default;
	}

	return $option;
}
add_filter( 'sequoia_options', 'sequoia_options_func', 10, 2 );
/**
 * WP Filesystem wrapper class.
 */
include_once( trailingslashit( get_template_directory() ) . 'inc/functions/wp-filesystem.php' );
//
/**
 * MENU FUNCTIONS.
 */
include( trailingslashit( get_template_directory() ) . 'inc/functions/menus.php' );
include( trailingslashit( get_template_directory() ) . 'inc/functions/menus-expand.php' );
//
/**
 *	WIDGETS FUNCTIONS.
 */
include( trailingslashit( get_template_directory() ) . 'inc/functions/widgets.php' );
//
/**
 *	BREADCRUMBS.
 */
include( trailingslashit( get_template_directory() ) . 'inc/functions/breadcrumbs.php' );
//
/**
 *	PAGINATION.
 */
include( trailingslashit( get_template_directory() ) . 'inc/functions/pagination.php' );
//
/**
 *	RUN ONCE class:
 */
include( trailingslashit( get_template_directory() ) . 'inc/functions/run_once_class.php' );
//
/*
 *	COMMENTS:
 */
include( trailingslashit( get_template_directory() ) . 'inc/functions/comments.php' );
//
/**
 *	AUDIO / VIDEO: 
 */
include( trailingslashit( get_template_directory() ) . 'inc/functions/audio-video.php' );
//
/**
 *	IMAGE / GALLERY:
 */
include( trailingslashit( get_template_directory() ) . 'inc/functions/image-gallery.php' );
//
/**
 *	ENQUEUE THEME STYLES:
 */
include( trailingslashit( get_template_directory() ) . 'inc/functions/enqueue_styles.php' );
//
/**
 *	ENQUEUE THEME SCRIPTS:
 */
include( trailingslashit( get_template_directory() ) . 'inc/functions/enqueue_scripts.php' );
//
/** 
 *	POST FORMATS:
 */
include( trailingslashit( get_template_directory() ) . 'inc/functions/post-meta.php' );
/** 
 *	POST META:
 */
include( trailingslashit( get_template_directory() ) . 'inc/functions/post-formats.php' );
//
/**
 *	MISCELANEUOUS POST FUNCTIONS:
 */
include( trailingslashit( get_template_directory() ) . 'inc/functions/misc_post_functions.php' );
//
/**
 *	ADMIN FUNCTIONS:
 */
include( trailingslashit( get_template_directory() ) . 'inc/functions/admin_functions.php' );
//
/**
 *	PLUGINS:
 */
include( trailingslashit( get_template_directory() ) . 'inc/functions/theme_inc_plugins.php' );
//
/**
 *	AJAX functions - used in custom blocks (prefixed with AS) created for Aqua Page Builder plugin
 */
 include( trailingslashit( get_template_directory() ) . 'inc/functions/ajax.php' ); //
/**
 *	WOOCOMMERCE
 */
include( trailingslashit( get_template_directory() ) . 'woocommerce/woocommerce-theme-edits.php' );
//
/**
 *	AQUA PAGE BUILDER - BLOCKS, CSS, JS:
 */
include( trailingslashit( get_template_directory() ) . 'inc/functions/page_builder.php' );
//
//
/**
 *  ADD (GOOGLE) ANALYTICS CODE
 *  
 *  @return Return_Description
 *  
 *  @details Details
 */
function sequoia_analytics_script(){
	
	$analytics = apply_filters( 'sequoia_options', 'google_analytics', '' );
	if ( $analytics ) {
		echo stripslashes($analytics);
	} 	
}
add_action('wp_head', 'sequoia_analytics_script');
/**
 *	ADD BODY CLASS CUSTOM SELETORS
 *  
 *  @param [array] $classes
 *  @return $classes
 *  
 */
function body_layout( $classes ) {
	$orientation = apply_filters( 'sequoia_options', 'orientation', 'horizontal' );
	if( $orientation == 'horizontal' ) {
		$class = 'horizontal-layout';
	}else{
		$class = 'vertical-layout';
	}
	$classes[] = $class;
	return $classes;
}
add_filter('body_class', 'body_layout');
//
/**
 *	GLOBALS AND CONSTANTS 
 *
 *	const PLACEHOLDER_IMAGE - used on all the places where no thumbnail image is not set.
 *	var $delimiter - used in breadcrumbs ( in directories inc/functions and woocommerce/shop)
 */
define ('PLACEHOLDER_IMAGE',  apply_filters( 'sequoia_options', "placeholder_image", get_template_directory_uri().'/img/no-image.jpg' ) );
define ('UNDERHEAD_IMAGE', apply_filters( 'sequoia_options', 'under_head', false ) );
$delimiter   = '<span class="delimiter"><span class="icon icon-arrow-right-6"></span></span>'; // delimiter between crumbs 
//
//
/**
 *	SEQUOIA 1.3.1. update notice and plugin installation
 *
 */
$sequoia_theme = wp_get_theme();
if ( $sequoia_theme->exists() ) {
	$version = $sequoia_theme->get( 'Version' ) ;
	
	
	if ( version_compare( $version, '1.3.1', '<=')  					// if Sequoia version < 1.3.1
		&& !get_option( "sequoia_131_updated" )						// option is filled
		&& !function_exists('init_sequoia_additional_functions') )	// plugin "Sequoia additional function" is active
	{
		add_action( 'admin_notices', 'sequoia_v_131_option' );
	
	}elseif( !function_exists('init_sequoia_additional_functions') ) {
		add_action( 'admin_notices', 'sequoia_plugin_not_active' );
	}
}
// Notice after Sequoia 1.3.1 installation / update
function sequoia_v_131_option() {
    
	echo '<div class="notice error sequoia-131-notice is-dismissible" >';
    echo '<p><strong>' . __( 'SEQUOIA THEME IMPORTANT NOTICE!<br>Since version 1.3.1 Sequoia WP theme uses "Sequoia Additional Functions" plugin, to which are transferred some "plugin territory" functionalities, existent in theme until v.1.3.1. Please install and activate the "Sequoia Additional Functions" in Appearance > Install plugins<br>Please, click (x) to close this message', 'sequoia' ) .' </strong></p>';
    echo '</div>';
    
}
//
function sequoia_plugin_not_active() {
	echo '<div class="notice error sequoia-plugin-not-active-notice is-dismissible" >';
	echo '<p><strong>'. __( 'Required plugin "Sequoia additional functions" is not active. Please go to Appearance > Install plugins and (install and)  activate the plugin.','sequoia' ) .'</strong></p>';
	echo '</div>';
}

// Ajax call ( in js/admin.js ) to update option and close notice on admin
add_action( 'wp_ajax_sequoia-update-option', 'sequoia_update_func' );// for logged in users
function sequoia_update_func() {
	update_option( "sequoia_131_updated", true );
}
/// end SEQUOIA 1.3.1 update notice 
?>