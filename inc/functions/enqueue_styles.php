<?php
/**
 *	REGISTER AND ENQUEUE ADMIN STYLES
 *
 */
function as_customAdminCSS() {
	wp_register_style('sequoia-admin-css', get_template_directory_uri(). '/css/admin_styles.css', 'style');
	wp_enqueue_style( 'sequoia-admin-css');
}
add_action('admin_head', 'as_customAdminCSS');
//
/**
 *	REGISTER AND ENQUEUE THEME STYLES
 *
 */
function as_theme_styles()  
{ 
	global $as_protocol;
	
	$t_url = get_template_directory_uri();
	
	$goo_body_setting = str_replace(' ','+', apply_filters( 'sequoia_options', 'google_body', array('face'=>'Raleway', 'size'=>'16px', 'weight'=>'400', 'color'=>'#333333') ) );
	$goo_head_setting = str_replace(' ','+', apply_filters( 'sequoia_options', 'google_headings', array('face'=>'Montserrat', 'weight'=>'800', 'color'=>'') ) );
	
	$google_body		= $goo_body_setting['face'];
	$google_headings	= $goo_head_setting['face'];

	// REGISTER GOOGLE FONTS
	wp_register_style('google-font-body', $as_protocol . '://fonts.googleapis.com/css?family='. $google_body .':300,400,600,700,800,400italic,700italic&subset=latin,latin-ext'  );
	wp_register_style('google-font-headings', $as_protocol . '://fonts.googleapis.com/css?family='. $google_headings .':300,400,600,700,800,400italic,700italic&subset=latin,latin-ext'  );
	
	
	/* REGISTER STYLES*/
	if( !wp_style_is('reset','registered ') ) {
		wp_register_style( 'reset', $t_url.'/css/reset.css' );
	}
	if( !wp_style_is('foundation','registered ') ) {
		wp_register_style( 'foundation',$t_url . '/css/foundation.min.css','' ,'' , 'all' ); // NEW
	}
	wp_register_style( 'glyphs',$t_url . '/css/glyphs.css','' ,'' , 'all' );
	wp_register_style( 'prettyPhoto', $t_url . '/css/prettyPhoto.css','' , '', 'all' );
	wp_register_style( 'sequoia-main-css', $t_url . '/style.css', '', '', 'all' );
	wp_register_style( 'woocommerce-as', $t_url . '/woocommerce/woocommerce.css', '', '', 'all' );
	wp_register_style( 'owl-carousel',$t_url . '/css/owl.carousel.css','', '', 'all' );
	
	/* ENQUEUE STYLES */
	wp_enqueue_style( 'google-font-headings' );
	wp_enqueue_style( 'google-font-body' );
	if( !wp_style_is('reset','enqueued ') ) {
		wp_enqueue_style( 'reset' );
	}
	if( !wp_style_is('foundation','enqueued ') ) {
		wp_enqueue_style( 'foundation' );
	}
	wp_enqueue_style( 'glyphs' );
	wp_enqueue_style( 'prettyPhoto' );
	wp_enqueue_style( 'sequoia-main-css' );
	wp_enqueue_style( 'woocommerce-as' );
	wp_enqueue_style( 'owl-carousel' );

	
	### THEME OPTIONS CSS AND JAVACRIPTS
	
	$dynamic_css_js = apply_filters( 'sequoia_options', 'dynamic_css_js', false );
	if( $dynamic_css_js ) {
		//DYNAMIC (AJAX) THEME OPTIONS CSS:
		wp_enqueue_style('options-styles', admin_url('admin-ajax.php') . '?action=dynamic_css',array(), '1.0.0', 'all');
	}else{
		
		// FILES CREATING:
		$theme_data		= wp_get_theme(); // get theme info
		$theme_slug		= sanitize_title( $theme_data ); // make Sequoia Fashion to be sequoia-fashion
		
		$uploads		= wp_upload_dir();
		$as_upload_dir	= trailingslashit($uploads['basedir']) . $theme_slug . '-options'; // DIRECTORY to uploads
		$as_upload_url	= trailingslashit($uploads['baseurl']) . $theme_slug . '-options'; // URL to uploads
		
		$as_upload_dir_exists = is_dir( $as_upload_dir );
		
		// THEME OPTIONS CSS :
		if( $as_upload_dir_exists ){
			
			wp_register_style('options-styles', $as_upload_url . '/theme_options_styles.css', 'style');
			
		}else{
		
			wp_register_style('options-styles', get_stylesheet_directory_uri() . '/admin_save_options/theme_options_styles.css', 'style');
		}
		wp_enqueue_style( 'options-styles');
		//
		//
	}
		
}
add_action('wp_enqueue_scripts', 'as_theme_styles');
//
//
/**
 *	DYNAMIC CSS - AJAX 
 *
 */
function dynamic_css() {
	
	$file = get_template_directory().'/admin_save_options/theme_options_styles.php';	
	require($file);
	exit;
}
add_action('wp_ajax_dynamic_css', 'dynamic_css');
add_action('wp_ajax_nopriv_dynamic_css', 'dynamic_css');
//
//
//
/**
 *	CUSTOMIZE LOGIN PAGE.
 *
 */
// Add custom css for login page
function as_login_stylesheet() {

	### THEME OPTIONS CREATING FILES AND REGISTER/ENQUEUING 
	
	$theme_data		= wp_get_theme(); // get theme info
	$theme_slug		= sanitize_title( $theme_data ); // make Sequoia Fashion to be sequoia-fashion
	
	$uploads		= wp_upload_dir();
	$as_upload_dir	= trailingslashit($uploads['basedir']) . $theme_slug . '-options'; // DIRECTORY to uploads
	$as_upload_url	= trailingslashit($uploads['baseurl']) . $theme_slug . '-options'; // URL to uploads
	
	$as_upload_dir_exists = is_dir( $as_upload_dir );
		
	if( $as_upload_dir_exists ){
	
		wp_enqueue_style( 'custom-login', $as_upload_url . '/custom_login_css.css' );
		
    }else{
	
		wp_enqueue_style( 'custom-login', get_template_directory_uri() . '/css/customlogin.css' );
	}
	
}
add_action( 'login_enqueue_scripts', 'as_login_stylesheet' );
//
// Change link and title from Wordpress.org to site homepage
function as_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'as_login_logo_url' );

function as_login_logo_url_title() {	
    return get_bloginfo('name');
}
add_filter( 'login_headertitle', 'as_login_logo_url_title' );