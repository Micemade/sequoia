<?php
/* ======= ADMIN FUNCTIONS ======= */
//
// ADD ADMIN TOOLBAR LINK TO THEME DOCUMENTATION
function as_admin_bar_render() {
    global $wp_admin_bar;
    // REMOVE MENU ITEM, like the Comments link, just by knowing the right $id
    //$wp_admin_bar->remove_menu('comments');
    // REMOVING SUBMENU like New Link.
    //$wp_admin_bar->remove_menu('new-link', 'new-content');
    // we can add a submenu item too
    $wp_admin_bar->add_menu( array(
        //'parent' => 'new-content',
        'id'	=> 'theme_documentation',
        'title'	=> __('THEME DOCUMENTATION','sequoia'),
        'href'	=> get_template_directory_uri().'/documentation/',
		'meta'	=>  array( 
				//'html'		=> '',
				'class'		=> 'theme_documentation',
				//'onclick'	=> '',
				'target'	=> '_blank',
				'title'		=> 'Open theme documentation in new window/tab'
				)
    ) );
}
// and we hook our function via
add_action( 'wp_before_admin_bar_render', 'as_admin_bar_render' );
//
//
// FIXED NAVBAR MOVE DOWN IF THERE IS  WP TOOLBAR
function as_toolbar_check () {
	if ( is_admin_bar_showing() ) {
		
		echo '<style>';
		echo '.tooltip  { margin-top:-40px !important; }';
		echo '#site-menu, .vertical-layout .mega-clone, .mobile-sticky.stuck, .stick-it-header , .st-menu  { top:32px !important; }';
		echo '@media screen and (max-width: 782px) { html #wpadminbar {  top:-46px; } }';
		echo '@media screen and (max-width: 600px) { .mobile-sticky.stuck { top:0 !important; } }';
		echo '@media screen and (max-width: 782px) { #site-menu-mobile { margin-top: 0px;} }';
		echo '</style>';
	}
}
add_action( 'wp_head', 'as_toolbar_check' );
//
//
/**
 *	CONTACT FORM FUNCTIONS
 *
 */
function hexstr($hexstr) {
	  $hexstr = str_replace(' ', '', $hexstr);
	  $hexstr = str_replace('\x', '', $hexstr);
	  $retstr = pack('H*', $hexstr);
	  return $retstr;
}
function strhex($string) {
	$hexstr = unpack('H*', $string);
	return array_shift($hexstr);
}
/**
 *	EDITOR IN META BOX ( PUT OTHER META BOXES ABOVE EDITOR)
 *
 */
add_action( 'add_meta_boxes', 'as_editor_metabox', 0 );
function as_editor_metabox() {
	global $post, $_wp_post_type_features;
	
	if( $post->post_type == 'page' )
		return;
		
	foreach ($_wp_post_type_features as $type => &$features) {
		if (isset($features['editor']) && $features['editor']) {
			unset($features['editor']);
			add_meta_box(
				'description',
				__('Content','sequoia'),
				'content_metabox',
				$type, 'normal', 'default'
			);
		}
	}
	add_action( 'admin_head', 'as_action_admin_head'); //white background
}
function as_action_admin_head() {
	?>
	<style type="text/css">
		.wp-editor-container{background-color:#fff;}
	</style>
	<?php
}
function content_metabox( $post ) {
	echo '<div class="wp-editor-wrap">';
	//the_editor is deprecated in WP3.3, use instead:
	wp_editor($post->post_content, 'content', array('dfw' => true, 'tabindex' => 1) );
	echo '</div>';
}
/**
 *	WPML STUFF: 
 *
 */
if( class_exists('SitePress') ) {

	define( 'WPML_ON', true );
	
	if ( ! function_exists( 'as_languages_list' ) ) {
	function as_languages_list(){
		if(function_exists('icl_get_languages')) {
			$languages = icl_get_languages('skip_missing=0&orderby=code');
		}
		if(!empty($languages)){
			echo '<div id="language_list"><ul>';
			foreach($languages as $l){
				
				$lang_name = icl_disp_language($l['native_name'], $l['translated_name']);
				
				echo '<li>';
				if($l['country_flag_url']){
					if(!$l['active']) echo '<a href="'.$l['url'].'" title="'.esc_attr( $lang_name ).'">';
					echo '<img src="'.$l['country_flag_url'].'" height="12" alt="'.$l['language_code'].'" width="18" />';
					if(!$l['active']) echo '</a>';
				}
				echo '</li>';
			}
			echo '</ul></div>';
		}
	}
	}
}
/**
 *	end WPML STUFF
 *
 */

/**
 *	FUNCTION HEX TO RGB - NEEDED FOR CSS BACKGROUND COLOR STYLES
 *
 */
function hex2rgb( $colour ) {
	
	if( !isset($colour[0]) )
		return;
	if ( $colour[0] == '#' ) {
			$colour = substr( $colour, 1 );
	}
	if ( strlen( $colour ) == 6 ) {
			list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
	} elseif ( strlen( $colour ) == 3 ) {
			list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
	} else {
			return false;
	}
	$r = hexdec( $r );
	$g = hexdec( $g );
	$b = hexdec( $b );
	//return array( 'red' => $r, 'green' => $g, 'blue' => $b );
	return $rgb = $r.', '. $g .', ' . $b ;	    
} 
/* end hex2rgb */


function wpa82718_scripts() {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script(
        'iris',
        admin_url( 'js/iris.min.js' ),
        array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),
        false,
        1
    );
    wp_enqueue_script(
        'wp-color-picker',
        admin_url( 'js/color-picker.min.js' ),
        array( 'iris' ),
        false,
        1
    );
    $colorpicker_l10n = array(
        'clear' => __( 'Clear','sequoia' ),
        'defaultString' => __( 'Default','sequoia' ),
        'pick' => __( 'Select Color','sequoia' )
    );
    wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n ); 

}
add_action( 'wp_enqueue_scripts', 'wpa82718_scripts', 100 );
//
/**
 *	LIST CUSTOM IMAGE SIZES IN MEDIA UPLOAD
 *
*/  
function as_image_sizes_mediapopup( $sizes ) {
	$new_sizes = array();
	$added_sizes = get_intermediate_image_sizes();
	foreach( $added_sizes as $key => $value) {
		$new_sizes[$value] = $value;
	}
	$new_sizes = array_merge( $new_sizes, $sizes );
	return $new_sizes;
}


/**
 *	BLOCK NON-ADMIN USERS FROM ADMIN PAGES
 *
*/
add_action( 'admin_init', 'blockusers_init' );
if ( !function_exists('blockusers_init') ) {

	function blockusers_init() {
		
		global $sequoia_woo_is_active;
		
		if( apply_filters( 'sequoia_options', 'blockusers', false ) ) {
		
			if ( is_admin() && ! current_user_can( 'administrator' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ){
				
				if( $sequoia_woo_is_active ) {
					$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
					if ( $myaccount_page_id ) {
						$myaccount_page_url = get_permalink( $myaccount_page_id );
						wp_redirect( $myaccount_page_url );
					}
					
				}else{
					wp_redirect( home_url() );
				}
				exit;
			}
		
		}
	}
}
/**
 *  REMOVE DEPRECATED FILES / DIRS
 *   
 *  @details delete files (mostly WC templates) not needed anymore (reducing WC templates)
 *  for easier WC / theme compatiblity and maintenance
 */
function sequoia_remove_deprecated_templates() {
	
	$files_to_remove = array( 
		// since sequoia 1.3.0 these files are redundant:
		'admin/medialibrary-uploader.php',
		'woocommerce/checkout/form-billing.php',
		'woocommerce/checkout/form-shipping.php',
		'woocommerce/myaccount/form-add-payment-method.php',
		'woocommerce/myaccount/form-edit-address.php',
		'woocommerce/myaccount/form-lost-password.php',
		'woocommerce/myaccount/view-order.php',
		'woocommerce/myaccount/my-downloads.php',
		'woocommerce/cart/cart.php',
		'woocommerce/global/breadcrumb-before2.3.php',
		// since 1.3.2
		'woocommerce/checkout/thankyou.php',
		'woocommerce/order/tracking.php',
		// since 1.3.3
		'woocommerce/cart/mini-cart.php',
		// since 1.4.1
		'woocommerce/order/form-tracking.php',
		'woocommerce/myaccount/form-login.php',
		'woocommerce/cart/shipping-calculator.php',
	);
	
	if( empty( $files_to_remove ) ) return;
	
	$wpfilesys = new DBI_Filesystem(); 	// inc/functions/wp-filesystem.php
	
	foreach( $files_to_remove as $file_to_remove ) {
		
		$file =  trailingslashit( get_template_directory() ) . $file_to_remove;
		if( $wpfilesys->file_exists( $file ) ) {
			$wpfilesys->unlink( $file );
		}
	}
}
function sequoia_remove_deprecated_dirs() {
	
	$dirs_to_remove = array(
		'inc/portfolio-cpt',
		// since 1.4.1
		'woocommerce/order',
	);
	
	if( empty( $dirs_to_remove ) ) return;
	
	$wpfilesys = new DBI_Filesystem(); 	// inc/functions/wp-filesystem.php
	
	foreach( $dirs_to_remove as $dir_to_remove ) {
		$dir =  trailingslashit( get_template_directory() ) . $dir_to_remove;
		if( $wpfilesys->is_dir( $dir ) ) {
			$wpfilesys->rmdir( $dir, true );
		}
	}
}
add_action('admin_init', 'sequoia_remove_deprecated_templates');
add_action('admin_init', 'sequoia_remove_deprecated_dirs');