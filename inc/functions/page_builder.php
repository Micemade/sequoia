<?php
/**
 *  Sequoia template select
 *  
 *  @param [string] $file - name of file
 *  @return required_once file
 *  
 */

add_filter( 'sequoia_template','sequoia_template_func', 10, 1 );
function sequoia_template_func( $file ) {
	
	$child_file		= get_stylesheet_directory() . '/inc/blocks/' . $file;
	$default_file	= get_template_directory() . '/inc/blocks/' . $file;
	
	if( is_child_theme() ) {
		if( file_exists( $child_file ) ) {
			$template = $child_file ;
		}else{
			$template = $default_file ;
		}
			
	}else{
		$template = $default_file;
	}
	
	return $template;
}

// IF PLUGIN IS ACTIVATED :
function page_builder_init() {

	global $pagenow;
	
	if(class_exists('AQ_Page_Builder')) {
		
		define('BLOCKS_PATH', get_template_directory() . '/inc/blocks/');
		
		require_once( apply_filters( 'sequoia_template','as-row-block.php' ) );	
		require_once( apply_filters( 'sequoia_template','as-ajax-cats.php' ) );
		require_once( apply_filters( 'sequoia_template','as-ajax-prod-cats.php' ) );		
		require_once( apply_filters( 'sequoia_template','as-filter-cats.php' ) );			
		require_once( apply_filters( 'sequoia_template','as-filter-products.php' ) );			
		require_once( apply_filters( 'sequoia_template','as-single-product-block.php' ) );
		require_once( apply_filters( 'sequoia_template','as-headings-block.php' ) );
		require_once( apply_filters( 'sequoia_template','as-banner-block.php' ) );
		require_once( apply_filters( 'sequoia_template','as-image-block.php' ) );
		require_once( apply_filters( 'sequoia_template','as-team-member-block.php' ) );
		require_once( apply_filters( 'sequoia_template','as-map-block.php' ) );
		require_once( apply_filters( 'sequoia_template','as-contactform-block.php' ) );
		require_once( apply_filters( 'sequoia_template','as-testimonials-block.php' ));
		require_once( apply_filters( 'sequoia_template','as-icon-block.php' ) );
		require_once( apply_filters( 'sequoia_template','as-slider-block.php' ) );
		require_once( apply_filters( 'sequoia_template','as-revolution-slider-block.php' ) );
		require_once( apply_filters( 'sequoia_template','as-images-slider-block.php' ) );
		require_once( apply_filters( 'sequoia_template','as-social-block.php' ) );
		require_once( apply_filters( 'sequoia_template','as-clear-block.php' ) );
		require_once( apply_filters( 'sequoia_template','as-onepager-menu-block.php' ) );
		require_once( apply_filters( 'sequoia_template','as-onepager-logo-block.php' ) );
		require_once( apply_filters( 'sequoia_template','as-prod-cats.php' ) );
		require_once( apply_filters( 'sequoia_template','as-button.php' ) );
		//
		require_once( apply_filters( 'sequoia_template','as-editor-block.php' ) );
		//
		//
		//UNREGISTER DEFAULT BLOCKS
		aq_unregister_block('AQ_Clear_Block');
		aq_unregister_block('AQ_Column_Block');
		//aq_unregister_block('AQ_Editor_Block');
		//
		// REGISTER THEME BLOCKS
		aq_register_block('AS_Row_Block');
		aq_register_block('AS_Editor_Block');
		aq_register_block('AS_Ajax_Categories');
		aq_register_block('AS_Ajax_Product_Categories');
		aq_register_block('AS_Filter_Categories');
		aq_register_block('AS_Filter_Products');
		aq_register_block('AS_Single_Product_Block');
		aq_register_block('AS_Headings_Block');
		aq_register_block('AS_Banner_Block');
		aq_register_block('AS_Image_Block');
		aq_register_block('AS_Team_Member_Block');
		aq_register_block('AS_Map_Block');
		aq_register_block('AS_Contact_Block');
		aq_register_block('AS_Testimonials');
		aq_register_block('AS_Icon_Block');
		aq_register_block('AS_Slider_Block');
		aq_register_block('AS_Revolution_Slider');
		aq_register_block('AS_Images_Slider');
		aq_register_block('AS_Social');
		aq_register_block('AS_Clear_Block');
		aq_register_block('AS_Onepager_Menu');
		aq_register_block('AS_Onepager_logo');
		aq_register_block('AS_Product_Categories');
		aq_register_block('AS_Button');
		
		#############################
		//
		// IF CURRENT ADMIN PAGE IS PAGE BUILDER EDIT PAGE:
		if( $pagenow == 'themes.php' && isset($_GET['page']) && ( $_GET['page'] == 'aqua-page-builder' || $_GET['page'] == 'aq-page-builder' )  ) {
			add_action( 'admin_head', 'as_admin_enqueue' );
		}
		
		/* Media Uploader  - AS edit: changed "type:hidden"*/
		function as_field_upload($field_id, $block_id, $media_id, $media_size, $media_type = 'image') {
			
			$output = '<input type="hidden" id="'. $block_id .'_'.$field_id.'" class="input-full input-upload" value="'.$media_id.'" name="aq_blocks['.$block_id.']['.$field_id.']" data-size="'.$media_size.'">';
			
			$output .= '<a href="#" class="aq_upload_button button" rel="'.$media_type.'">Upload</a>';
			
			return $output;
		}
		function as_hidden_input($field_id, $block_id, $input, $type = 'hidden') {
			
			$output = '<input type="'. $type .'" id="'. $block_id .'_'.$field_id . '" class="input-hidden" value="'. esc_attr($input) .'" name="aq_blocks['.$block_id.']['.$field_id.']">';
		
			return $output;
		}
		
		/* For AS Icon Block */
		function icons_styles_script() {
		
			wp_register_style( 'glyphs_css', get_template_directory_uri() . '/css/glyphs.css', false, '1.0.0' );
			wp_enqueue_style( 'glyphs_css' );

		}
		add_action( 'admin_enqueue_scripts', 'icons_styles_script' );
		//
		//			
	}
		
} //endif is class exists

add_action('init','page_builder_init');

//
/**
 *	GET TAXONOMIES TERMS IN AJAX AND FILTER BLOCKS:
 *
 */
function as_terms_for_blocks_func( $taxonomy ) {

	if( defined('WPML_ON') ) { // IF WPML IS ACTIVATED
				
		$terms = get_terms( $taxonomy,'hide_empty=1' );
		if ( !empty( $terms ) ){
			foreach ( $terms as $term ) {
				if($term->term_id == icl_object_id($term->term_id, $taxonomy,false,ICL_LANGUAGE_CODE)){
					$terms_arr[$term->slug]= $term->name ;
				}
			}
		}else{
			$terms_arr = array();
		}
		
	}else{
	  
		$terms = get_terms( $taxonomy,'hide_empty=1');
		if ( $terms ) {
			foreach ($terms as $term) {
				$terms_arr[$term->slug]= $term->name ;
			}
		}else{
			$terms_arr = array();
		}
	}
	
	return $terms_arr;

}
add_filter('as_terms','as_terms_for_blocks_func', 10, 1);
//
/**
 *	REPLACE ORIGINAL WITH ALIGATOR STUDIO (AS) JavaScript and CSS style
 *
 */
function as_admin_enqueue()  {
	
	//REPLACE ORIG: PLUGIN'S JS WITH THEME JS
	// used deregister_aqpb_functions() function from "Sequoia Additional Functions" plugin
	if( function_exists('init_sequoia_additional_functions') ) {
		deregister_aqpb_functions();
	} 
	
	wp_register_script('aqpb-js-as', get_template_directory_uri(). '/inc/blocks/aqpb_as.js', array('jquery'), '', true);
	wp_enqueue_script('aqpb-js-as');
	
	wp_register_script('aqpb-fields-js-as', get_template_directory_uri() . '/inc/blocks/aqpb-fields.js', array('jquery'), time(), true);
	wp_enqueue_script('aqpb-fields-js-as');
	
	wp_enqueue_script('jquery-ui-slider');
	
	wp_register_style( 'aqpb-css-as', get_template_directory_uri() . '/inc/blocks/aqpb-as.css' );
	wp_enqueue_style( 'aqpb-css-as' );
	
}
?>