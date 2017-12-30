<?php

add_action('init','of_options');

if (!function_exists('of_options')) {
function of_options(){

global $sequoia_woo_is_active;

include('google-fonts.php');

/***************** GET POST CATEGORIES ****************/
$post_cats = array();  
$post_cats_obj = get_categories('hide_empty=0');
if ($post_cats_obj) {
	foreach ($post_cats_obj as $cat) {
		$post_cats[$cat->cat_ID] = $cat->name ;
	}
}else{
	$post_cats[0] = '';
}
//$categories_tmp = array_unshift($blog_categories, array("= Select Blog Category =",'0') );    
//$blog_categories_tmp = $blog_categories;    



/***************** GET PRODUCT CATEGORIES ****************/
if( $sequoia_woo_is_active ) {
	$product_cats = array();  
	$product_cats_obj = get_terms('product_cat','hide_empty=0');
}
if ( !empty( $product_cats_obj ) ) {
	foreach ($product_cats_obj as $product_cat) {
		$product_cats[$product_cat->term_id] = $product_cat->name ;
	}
}else{
	$product_cats[0] = '';
}

/***************** GET PORTFOLIO CATEGORIES ****************/
if( post_type_exists( 'portfolio' ) ) {
	$portfolio_cats = array();  
	$portfolio_cats_obj = get_terms('portfolio_category','hide_empty=0');
	if ($portfolio_cats_obj) {
		foreach ($portfolio_cats_obj as $portfolio_cat) {
			$portfolio_cats[$portfolio_cat->term_id] = $portfolio_cat->name ;
		}
	}else{
		$portfolio_cats[0] = '';
	}
}



//Access the WordPress Pages via an Array
$of_pages = array();
$of_pages_obj = get_pages('sort_column=post_parent,menu_order');    
foreach ($of_pages_obj as $of_page) {
    $of_pages[$of_page->ID] = $of_page->post_name; }
$of_pages_tmp = array_unshift($of_pages, "Select a page:");       


// Access Aqua Page templates
$template_posts = get_posts( array( 
					'post_type' 		=> 'template', 
					'posts_per_page'	=> -1,
					'post_status' 		=> 'publish',
					'order'				=> 'ASC',
					'orderby'			=> 'title'
		    		)
				);
if($template_posts) {
	foreach( $template_posts as $templ ){
		$templates[$templ->ID] = $templ->post_title; 
	}
}else{
	$templates = array();
}

		
// BLOCK NAME | BLOCK WIDTH
function sequoia_vertical_header_blocks_f( $void ) {
	$vertical_header_blocks_arr = array( 
		"enabled" => array (
			"placebo"			=> "placebo|1", //REQUIRED!
			"site_title"		=> "Site title or logo| 100",
			"cart"				=> "Shopping cart| 100",
			"search"			=> "Search| 100",
			"menu"				=> "Menu| 100",
			"contact_social"	=> "Contact / Social| 100",
			"widgets_block"		=> "Widgets block| 100",
			
		),	
		"disabled" => array (
			"placebo"			=> "placebo| 1", //REQUIRED!
			"widgets_block_2"	=> "Widgets block 2| 100",
			"widgets_block_3"	=> "Widgets block 3| 100"
		), 

	);
	return $vertical_header_blocks_arr;
}
add_filter('sequoia_vertical_header_blocks','sequoia_vertical_header_blocks_f', 10, 1);

function sequoia_mobile_header_blocks_f( $void ) {
	$mobile_header_blocks_arr = array( 
		"enabled" => array (
			"placebo"			=> "placebo|1", //REQUIRED!
			"search"			=> "Search| 100",
			"menu"				=> "Menu mobile| 100",
			"cart"				=> "Shopping cart| 100",
			"contact_social"	=> "Contact / Social| 100",
		),	
		"disabled" => array (
			"placebo"			=> "placebo| 1", //REQUIRED!
			"widgets_block"		=> "Widgets block| 100",
			"widgets_block_2"	=> "Widgets block 2| 100",
			"widgets_block_3"	=> "Widgets block 3| 100"

		), 
	);
	
	return $mobile_header_blocks_arr;
}
add_filter( 'sequoia_mobile_header_blocks', 'sequoia_mobile_header_blocks_f', 10, 1 );


//Stylesheets Files Reader
$alt_stylesheet_path = LAYOUT_PATH;
$alt_stylesheets = array();

if ( is_dir($alt_stylesheet_path) ) {
    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
            if(stristr($alt_stylesheet_file, ".css") !== false) {
                $alt_stylesheets[] = $alt_stylesheet_file;
            }
        }    
    }
}

//Background Images Files Reader
$bg_images_path = get_template_directory() . '/img/bg/'; // change this to where you store your bg images
$bg_images_url = get_template_directory_uri().'/img/bg/'; // change this to where you store your bg images
$bg_images = array(); 

if ( is_dir($bg_images_path) ) {
    if ($bg_images_dir = opendir($bg_images_path) ) { 
        while ( ($bg_images_file = readdir($bg_images_dir)) !== false ) {
            if(stristr($bg_images_file, ".png") !== false || stristr($bg_images_file, ".jpg") !== false) {
                $bg_images[] = $bg_images_url . $bg_images_file;
            }
        }    
    }
}

/*-----------------------------------------------------------------------------------*/
/* TO DO: Add options/functions that use these */
/*-----------------------------------------------------------------------------------*/

//More Options
$uploads_arr = wp_upload_dir();
$all_uploads_path = $uploads_arr['path'];
$all_uploads = get_option('of_uploads');
$body_repeat = array("no-repeat","repeat-x","repeat-y","repeat");
$body_pos = array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right");

// Image Alignment radio box
$of_options_thumb_align = array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center"); 

// Image Links to Options
$of_options_image_link_to = array("image" => "The Image","post" => "The Post"); 


/*-----------------------------------------------------------------------------------*/
/* The Options Array */
/*-----------------------------------------------------------------------------------*/

// Set the Options Array
global $of_options;

$images_url =  ADMIN_DIR . 'images/';

$of_options = array();

//=================== GENERAL SETTINGS TAB ================================ 

$of_options[] = array( 
					"name"			=> "General Settings",
					"id"			=> "TAB - general",
                    "type"			=> "heading",
					"std"			=> ""
					);

$of_options[] = array(
					"name"			=> "DYNAMIC CSS AND JS ?",
					"desc"			=> '',
					"id"			=> "dynamic_css_js",
					"std"			=> 0,
					"on"			=> "True",
					"off"			=> "False",
					"type"			=> "switch"
					);
$of_options[] = array(
					"name"			=> "",
					"desc_html"		=> '<strong>DYNAMIC CSS AND JS FILES</strong> are AJAX created and loaded styles and scripts via Wordpress admin ajax. This is alternative to default, creating css and javascript files upon theme options saving.<br><strong>Use in case of write/execute restrictive servers</strong><br><br>',
					"id"			=> "dynamic_css_js_info",
					"std"			=> '',
					"type"			=> "html"
					); 
					
$of_options[] = array( 
					"name"			=> "Site logo image",
					"desc"			=> "Upload your logo image used for site header. NOTE: will be used as a login logo, too. ",
					"id"			=> "site_logo",
					"std"			=> get_stylesheet_directory_uri().'/img/logo.png',
					"mod"			=> "min",
					"type"			=> "media");		

$of_options[] = array(
					"name"			=> "Logo, site title and site description on/off",
					"desc"			=> "Choose if you want to display logo or site title and description. Site title are set in <a href='options-general.php'>WP general settings</a><br /><br />NOTE: If logo display is off, the textual site title will be displayed",
					"id"			=> "logo_desc",
					"std"			=> array("logo_on"),
				  	"type"			=> "multicheck",
					"options"		=> array(
							"logo_on"		=> "Display Logo ?",
							"desc_on"		=> "Display site description"
					)
					);	
/* 	Favicon is handled by WP function site_icon() (from WP4.0)			
$of_options[] = array(
					"name"			=> "Custom Favicon",
					"desc"			=> "Upload a Png/Gif image that will represent your website's favicon.<br /><br /><b>NOTE: Image size should be minimum 144x144 px, because of some high resolution devices.</b>",
					"id"			=> "custom_favicon",
					"std"			=> get_template_directory_uri(). '/img/favicon.png',
					"type"			=> "upload"); 
*/
$of_options[] = array(
					"name"			=> "Placeholder image",
					"desc"			=> "The image to show when no post/product/portfolio image is uploaded.",
					"id"			=> "placeholder_image",
					"std"			=> get_template_directory_uri().'/img/no-image.jpg',
					"mod"			=> "min",
					"type"			=> "media");
					
$of_options[] = array(
					"name"			=> "",
					"desc_html"		=> '<h3>WP login and admin settings</h3><hr>If "Customize login page" is set to True, the default WP logo will be replaced with site logo, and the custom background image will be applied.',
					"id"			=> "admin_group",
					"std"			=> '',
					"type"			=> "html"
					); 					

$of_options[] = array(
					"name"			=> "Customize login page",
					"desc"			=> 're-brand login page by using site logo and custom login background image',
					"id"			=> "custom_login_page",
					"std"			=> 1,
					"on"			=> "True",
					"off"			=> "False",
					"type"			=> "switch"
					);
					
$of_options[] = array(
					"name"			=> "Login page background image",
					"desc"			=> "The background image to show on admin login page.",
					"id"			=> "admin_back_image",
					"std"			=> get_stylesheet_directory_uri().'/img/loginback.jpg',
					"mod"			=> "min",
					"type"			=> "media");	
					
$of_options[] = array(
					"name"			=> "Block non-admin users from WP dashboard",
					"desc"			=> 'block non-administrators from WP admin backend and redirect to My Account page (if WooCommerce is active) or to site Home page',
					"id"			=> "blockusers",
					"std"			=> 1,
					"on"			=> "True",
					"off"			=> "False",
					"type"			=> "switch"
					);	

$of_options[] = array(
					"name"			=> "",
					"desc_html"		=> '<h3>Layout settings</h3>',
					"id"			=> "layout_settings_group",
					"std"			=> '',
					"type"			=> "html"
					); 		

$of_options[] = array(
					"name"			=> "UNDER HEADER titles background image ",
					"desc"			=> 'place header image of pages, archive and singles UNDER header with menu ( for combination with transparent header )',
					"id"			=> "under_head",
					"std"			=> 0,
					"folds"			=> 1,
					"on"			=> "True",
					"off"			=> "False",
					"type"			=> "switch"
					);	
					
$of_options[] = array(
					"name"			=> "",
					"desc_html"		=> '<p><strong></strong>Pages, archive, shop and single pages have title background image. That image can be moved UNDER HEADER, to the page top - combined with header opacity this setting can make your page really stand out.</p>Edit header image in <strong>Home Settings, Shop settings, Blog settings or Portfolio settings</strong></a> ',
					"id"			=> "under_header_info",
					"std"			=> '',
					"type"			=> "html"
					); 	
					
		
										
					
$of_options[] = array(
					"name"			=> "Layout",
					"desc"			=> 'Choose layout for the site - APPLIES TO MAIN CONTENT, NOT TO THE SIDE MENU OR HEADER MENU. This setting will apply to all the site - blog, product, portfolio archives and taxonomy pages, single post  and single pages. <br /> Can be overriden in SHOP SETTINGS and/or individual page settings. ',
					"id"			=> "layout",
					"std"			=> "float_left",
					"type"			=> "images",
					"options"		=> array(
						'float_left'		=> $images_url . 'layout_fl_left.png',
						'float_right'		=> $images_url . 'layout_fl_right.png',
						'full_width'		=> $images_url . 'layout_full.png'	
						)
					);

$of_options[] = array(
					"name"			=> "Use SmoothScroll",
					"desc"			=> "SmoothScroll is jQuery plugin for smooth scrolling of content.",
					"id"			=> "use_nice_scroll",
					"std"			=> 1,
					"on"			=> "Use SmoothScroll",
					"off"			=> "Disable SmoothScroll",
					"folds"			=> 1,
					"type"			=> "switch"
					);

					
$of_options[] = array(
					"name"			=> "Use Nice scroll on SIDE MENU and MEGA MENUS",
					"desc"			=> "if there are a lot of items in side menu or mega menus it may happen they will go off screen. Use this to make them appear with scroll.",
					"id"			=> "use_nice_scroll_menus",
					"std"			=> 1,
					"on"			=> "Use Nice scroll",
					"off"			=> "Disable Nice scroll",
					"folds"			=> 0,
					"type"			=> "switch"
					);
					
					
$of_options[] = array(
					"name"			=> "Show breadcrumbs ?",
					"desc"			=> "",
					"id"			=> "show_breadcrumbs",
					"std"			=> 1,
					"on"			=> "Breadcrumbs on",
					"off"			=> "Breadcrumbs off",
					"type"			=> "switch"
				);

	
$of_options[] = array(
					"name"			=> "Display languages flags? (<strong>WPML plugin required</strong>) ",
					"desc"			=> "If you installed, activated and configured WMPL plugin, choose if you want to display language flags in header",
					"id"			=> "lang_sel",
					"std"			=> 1,
					"on"			=> "Show flags",
					"off"			=> "Hide flags",
					"folds"			=> 0,
					"type"			=> "switch"
				);
				
$of_options[] = array(
					"name"			=> "Sidebar missing widgets replacement content",
					"desc"			=> "how should sidebars or widget areas appear in case they are empty of widgets.",
					"id"			=> "empty_sidebar_meta",
					"std"			=> "empty_notice",
					"type"			=> "radio",
					"options"		=> array(
						'meta_login'	=> 'Meta login',
						'empty_notice'	=> 'Notice for sidebar without widgets',
						'none'			=> 'None'
						)
					);


$of_options[] = array(
					"name"			=> "Use page preloader effect",
					"desc"			=> "preloader effect is used te make smooth page to page transtion when link outside the page is clicked (not used for anchors)",
					"id"			=> "use_preloader",
					"std"			=> 0,
					"on"			=> "Use preloader",
					"off"			=> "Don't use",
					"folds"			=> 0,
					"type"			=> "switch"
					);


$of_options[] = array(  
					"name"			=> "Hide edit pages metaboxes",
					"desc"			=> "force hiding metaboxes in post, pages or custom post type edit pages, not used with theme (Exceprt, Revisions, Slug)",
					"id"			=> "hidden_metaboxes",
					"std"			=> 1,
					"on"			=> "Hide metaboxes",
					"off"			=> "Show metaboxes",
					"folds"			=> 0,
					"type"			=> "switch"
				);


$of_options[] = array(
					"name"			=> "Tracking Code",
					"desc"			=> "Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.",
					"id"			=> "google_analytics",
					"std"			=> "",
					"type"			=> "textarea");  

  					
$of_options[] = array( 
					"name"			=> "Contact Form email",
					"desc"			=> "If you want to use Contact Form - Page builder block, you can enter your email address here. Default email address is admin address enteres in <b><a href='options-general.php'>WP general settings</a></b> (Email address field)  ",
					"id"			=> "contact_email",
					"std"			=> get_option('admin_email'),
					"type"			=> "text"); 

$of_options[] = array(
					"name"			=> "Demo mode",
					"desc"			=> 'to display demo switcher (as in Theme forest theme demo) and theme demo variables (for developers - check theme_demo_vars.php and theme_demo_switcher.php files)',
					"id"			=> "demo_mode",
					"std"			=> 0,
					"on"			=> "Demo mode on",
					"off"			=> "Demo mode false",
					"type"			=> "switch"
					);				
/****************************** SHOP SETTINGS TAB *************************************/					
					
					
$of_options[] = array(
					"name"			=> "Shop Settings",
					"id"			=> "TAB - shop settings",
                    "type"			=> "heading",
					"std"			=> ""
					); 


$of_options[] = array(
					"name"			=> "Header cart icon action",
					"desc"			=> "Choice between popup menu with mini cart or redirecting to cart page",
					"id"			=> "cart_action",
					"std"			=> "popup",
					"type"			=> "radio",
					"options"		=> array(
									'popup'		=> 'Popup mini-cart menu',
									'page'		=> 'Cart page'
									)
					);	

$of_options[] = array(
					"name"			=> "Catalog page and single product numbers",
					"desc"			=> "set the numbers for catalog (and product taxonomies) page, including the numbers for related items on single product page.",
					"id"			=> "products_page_settings",
					"mod"			=> "mini",
					"multi"			=> true, // if true - in 'std' goes array
					"std" 			=> array(
									"Products per page"	=> 12,
									"Products columns"	=> 3,
									"Related total"		=> 3,
									"Related columns"	=> 3
								),
					"type"			=> "text"
					);

$of_options[] = array(
					"name"			=> "Single product page - upsell products total",
					"desc"			=> "",
					"id"			=> "upsell_total",
					"mod"			=> "mini",
					"std"			=> 3,
					"type"			=> "text"
					); 	
				
$of_options[] = array(
					"name"			=> "Single product page -upsell products in row",
					"desc"			=> "",
					"id"			=> "upsell_in_row",
					"mod"			=> "mini",
					"std"			=> 3,
					"type"			=> "text"
					); 	
					
					
$of_options[] = array(
					"name"			=> "Cross sell products in row",
					"desc"			=> "",
					"id"			=> "cross_sell_in_row",
					"mod"			=> "mini",
					"std"			=> 3,
					"type"			=> "text"
					); 	
					
					
$of_options[] = array(
					"name"			=> "Product categories catalog numbers",
					"desc"			=> "set the numbers for display of product categories (categories images).",
					"id"			=> "prod_cats_sett",
					"mod"			=> "mini",
					"multi"			=> true, // if true - in 'std' goes array
					"std" 			=> array(
									"Categories columns"=> 3,
									"Image width"		=> '',
									"Image height"		=> '',
								),
					"type"			=> "text"
					);
					
$of_options[] = array(
					"name"			=> "",
					"desc_html"		=> '<strong>Category columns</strong> - if not set (empty), the "product columns" setting will be used <br><br> <strong>Image width</strong> and <strong>image height</strong> - both values must be set to create custom category image size - values are in <strong>px</strong> (type in only the number)<br><br>',
					"id"			=> "prod_cat_info",
					"std"			=> '',
					"type"			=> "html"
					); 	
					
					
$of_options[] = array( 
					"name"			=> "Category images text color",
					"desc"			=> "appears when hovering with cursor over image",
					"id"			=> "cat_item_text_color",
					"std"			=> "",
					"type"			=> "color"
					);   				
	
					

					
$of_options[] = array(
					"name"			=> "Catalog shopping action buttons",
					"desc"			=> "Choose which buttons will be displayed for each product in catalog page - NOTE: for Wishlist the YITH WooCommerce Wishlist plugin must be installed and activated",
					"id"			=> "catalog_buttons",
					"std"			=> array('shop_quick','shop_buy_action','shop_wishlist'),
				  	"type"			=> "multicheck",
					"options"		=> array(
									'shop_quick'		=> 'Quick buy button',
									'shop_buy_action'	=> 'Add to cart / Select options',
									'shop_wishlist'		=> 'Wishlist'
								)
					);
					
$of_options[] = array(
					"name"			=> "Catalog full width page",
					"desc"			=> 'If set <strong>float left</strong> or <strong>float right</strong> for site-wide layout, you can still make cart and checkout in full width here.',
					"id"			=> "products_full_width",
					"std"			=> 1,
					"folds"			=> 1,
					"on"			=> "Full width",
					"off"			=> "No full width",
					"type"			=> "switch"
				);
$of_options[] = array(
					"name"			=> "Single product full width page",
					"desc"			=> 'If set <strong>float left</strong> or <strong>float right</strong> for site-wide layout, you can still make cart and checkout in full width here.',
					"id"			=> "single_full_width",
					"std"			=> 0,
					"folds"			=> 1,
					"on"			=> "Full width",
					"off"			=> "No full width",
					"type"			=> "switch"
				);				
				
$of_options[] = array(
					"name"			=> "Cart, checkout full width page",
					"desc"			=> 'If set <strong>float left</strong> or <strong>float right</strong> for site-wide layout, you can still make cart and checkout in full width here. <br /><br />NOTE: you can also choose full width page template in Page edit.',
					"id"			=> "shop_cart_full_width",
					"std"			=> 1,
					"folds"			=> 1,
					"on"			=> "Full width",
					"off"			=> "No full width",
					"type"			=> "switch"
				);
					
$of_options[] = array(
					"name"			=> "Products (catalog) page display settings",
					"desc"			=> "",
					"id"			=> "products_settings",
					"std"			=> array(),
				  	"type"			=> "multicheck",
					"options"		=> array(
									'disable_zoom_button' => 'Disable zoom button',
									'disable_link_button' => 'Disable link button'
								)
					);
					
$of_options[] = array(
					"name"			=> "Products (catalog) page images format",
					"desc"			=> "Choose between theme PREDEFINED formats or WooCommerce plugin's <strong>Shop catalog </strong>image format (can be customized in WooCommerce settings).",
					"id"			=> "shop_image_format",
					"std"			=> "as-portrait",
					"type"			=> "radio",
					"options"		=> array(
									'as-portrait'	=> 'Sequoia portrait',
									'as-landscape'	=> 'Sequoia landscape',
									'plugin'		=> 'WooCommerce Plugin image settings'
									)
					);

$of_options[] = array(
					"name"			=> "Quick view images format",
					"desc"			=> "Choose between theme PREDEFINED formats or WooCommerce plugin's <strong>Single product image</strong> format for 'Quick view' product images.",
					"id"			=> "single_product_image_format",
					"std"			=> "as-portrait",
					"type"			=> "radio",
					"options"		=> array(
									'as-portrait'	=> 'Sequoia portrait',
									'as-landscape'	=> 'Sequoia landscape',
									'plugin'		=> 'WooCommerce Plugin image settings'
								)
					);



function animations_array() {
		
	include ( get_template_directory() .'/inc/functions/animations-icons-arrays.php' );
	return $block_enter_anim_arr;

}
$of_options[] = array(
					"name"			=> "Catalog page viewport animation",
					"desc"			=> "choose the product items animation when catalog page loads and when product enters browser viewport. The products will consecutevly load, depending on their order of appearance.",
					"id"			=> "prod_enter_anim",
					"std"			=> "fadeIn",
					"type"			=> "select",
					"options"		=> animations_array()
					);
					
$of_options[] = array(
					"name"			=> "Product image hover animation",
					"desc"			=> "",
					"id"			=> "prod_hover_anim",
					"std"			=> "anim-0",
					"type"			=> "select",
					"options"		=> array(
						'none'		=> 'None',
						'anim-0'	=> 'Opacity',
						'anim-1'	=> 'Scale down',
						'anim-2'	=> 'Scale up',
						'anim-3'	=> 'Slide from left',
						'anim-4'	=> 'Slide from right',
						'anim-5'	=> 'Slide down',
						'anim-6'	=> 'Slide up',
						'anim-7'	=> 'Rotate from left',
						'anim-8'	=> 'Rotate from right',
						'anim-9'	=> 'Flip in X',
						'anim-10'	=> 'Flip in Y',
						)
					);						
					
	
$of_options[] = array(
					"name"			=> "Product info hover animation",
					"desc"			=> "",
					"id"			=> "prod_data_anim",
					"std"			=> "data-anim-01",
					"type"			=> "select",
					"options"		=> array(
						'none'			=> 'None',
						'data-anim-01'	=> 'Scale down',
						'data-anim-02'	=> 'Scale up',
						'data-anim-03'	=> 'Slide from left',
						'data-anim-04'	=> 'Slide from right',
						'data-anim-05'	=> 'Slide down',
						'data-anim-06'	=> 'Slide up',
						'data-anim-07'	=> 'Rotate from left',
						'data-anim-08'	=> 'Rotate from right',
						'data-anim-09'	=> 'Flip in X',
						'data-anim-10'	=> 'Flip in Y',
						)
					);					
					
					
$of_options[] = array(
					"name"			=> "Display shop title background image ?",
					"desc"			=> 'for catalog page or product categories page (if no product category images is set), it is required to set background image (switch to "Display" and the upload manager will appear)',
					"id"			=> "shop_title_bcktoggle",
					"std"			=> 1,
					"folds"			=> 1,
					"on"			=> "Display",
					"off"			=> "Hide",
					"type"			=> "switch"
				);					
					
$of_options[] = array(
					"name"			=> "Shop title background image",
					"desc"			=> "upload image for shop (product page) title background. For product categories the product category image will be used (must be uploaded in WooCommerce > Categories)",
					"id"			=> "shop_title_backimg",
					"fold"			=> "shop_title_bcktoggle", /* the folding hook */
					"std"			=> get_template_directory_uri(). '/img/header-shop.jpg',
					"type"			=> "media"
					);

					
					
//=================== TYPOGRAPHY TAB ================================ 

$of_options[] = array(
					"name"			=> "Fonts",
					"id"			=> "TAB - fonts",
					"type"			=> "heading",
					"std"			=> ""
					);
		

$of_options[] = array( 
					"name"			=> "Google fonts or Typekit fonts?",
					"desc"			=> "",
					"id"			=> "google_typekit_toggle",
					"std"			=> "google",
					"type"			=> "radio",
					"options" => array(
							'google'		=> 'Google fonts',
							'typekit'		=> 'Typekit fonts',
							'none'			=> 'Only system fonts'
						)
					);
	
		
$of_options[] = array(
					"name"			=> "HEADINGS FONT : Google Font",
					"desc"			=> "choose <strong>google font</strong> for your headings, titles, etc ... Preview font size is fixed.",
					"id"			=> "google_headings",	
					"fold"			=> "google", /* the radio hook for show */
					"unfold"		=> "google_typekit_toggle",/* the radio hook for hide */
					"std"			=> array('face'=>'Montserrat', 'weight'=>'800', 'color'=>''),
					"type"			=> "select_google_font",
					"preview"		=> array(
									"text" => "My heading is my title.", //this is the text from preview box
									"size" => "36px" //this is the text size from preview box
						),
					//"options"		=> apply_filters("as_google_fonts", array()),
					);
$of_options[] = array( 
					"name"			=> "BODY FONT - Google Font",
					"desc"			=> "choose <strong>google font</strong> for your headings, titles, etc ... Preview font size iz fixed.",
					"id"			=> "google_body",
					"fold"			=> "google",
					"unfold"		=> "google_typekit_toggle",/* the radio hook for hide */
					"std"			=> array('face'=>'Raleway', 'size'=>'16px', 'weight'=>'400', 'color'=>'#333333'),
					"type"			=> "select_google_font",
					"preview"		=> array(
									"text" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.", //this is the text from preview box
									"size" => "15px" //this is the text size from preview box
						),
					//"options"		=> apply_filters("as_google_fonts", array()),
					);			
					
$of_options[] = array(
					"name"			=> "TYPEKIT FONTS: Typekit font kit ID",
					"desc"			=> "You can find your Typekit font kit ID on typekit.com under Kit Editor -> Embed Code.",
					"id"			=> "typekit_id",
					"std"			=> '',
					"fold"			=> "typekit", /* the radio hook for show */
					"unfold"		=> "google_typekit_toggle",/* the radio hook for hide */
					"type"			=> "text"
					); 

$of_options[] = array(
					"name"			=> "",
					"desc_html"		=> 'Enter the Typekit ID - find your Typekit ID on <strong><a href="http://typekit.com">typekit.com</a></strong> under Kit Editor -> Embed Code. sequoia theme uses two main fonts grouped for usage in following css selectors: <ol><li>body, #site-menu, .block-subtitle, .bottom-block-link a, .button,  .onsale, .taxonomy-menu h4, .tk-brandon-grotesque,   button, input#s, input[type="button"], input[type="email"], input[type="reset"], input[type="submit"], input[type="text"], select,  textarea, ul.post-portfolio-tax-menu li a</li><li>h1,  h2, h3,  h4,  h5,  h6, .billing_country_chzn, .chzn-drop, .navbar .nav, .price, .tk-adobe-garamond-pro, footer</li></ol> Copy each group of selectors to Typekit font selected on your preference ( use Typekit Kit Editor ).',
					"id"			=> "typekit_signin",
					"std"			=> '',
					"fold"			=> "typekit", /* the radio hook for show this */
					"unfold"		=> "google_typekit_toggle",/* the radio hook for hide this */
					"type"			=> "html"
					); 

$of_options[] = array(
					"name"			=> "",
					"desc_html"		=> '<h3>ADDITIONAL GOOGLE FONTS</h3><p>add font or the font list, separated with comma, for exaple - <strong>Open Sans, Monserrat, Lora</strong>.</p>',
					"id"			=> "add_ggl_fonts_expl",
					"fold"			=> "google", /* the radio hook for show */
					"unfold"		=> "google_typekit_toggle",/* the radio hook for hide */
					"std"			=> '',
					"type"			=> "html"
					); 									   
$of_options[] = array(
					"name"			=> "Google fonts",
					"desc"			=> "enter font or the font list, separated with comma, for exaple - Open Sans, Monserrat, Lora. NOTE: only letters, numbers, spaces and commas will be accepted as input.",
					"fold"			=> "google", /* the radio hook for show */
					"unfold"		=> "google_typekit_toggle",/* the radio hook for hide */
					"id"			=> "added_google_fonts",
					"std"			=> "",
					"type"			=> "textarea"
					);  
					
////////// SYSTEM FONTS:
					
$of_options[] = array(
					"name"			=> "HEADINGS - system font",
					"desc"			=> "Specify <strong>system</strong> fonts for headings, titles etc ...",
					"id"			=> "sys_heading_font",
					"std"			=> array('face' => '','weight' => '', 'style' => '' ,'color' => ''),
					"type"			=> "typography"
					);
		
$of_options[] = array(
					"name"			=> "BODY FONT - system fonts",
					"desc"			=> "Specify <strong>system</strong> fonts for body.",
					"id"			=> "sys_body_font",
					"std"			=> array('face' => '','style' => '','color' => '', 'size' => '', 'height' => ''),
					"type"			=> "typography");  		

					
					
//=================== STYLING TAB ================================ 
												  
$of_options[] = array(
					"name"			=> "Styling Options",
					"id"			=> "TAB - styling",
					"type"			=> "heading",
					"std"			=> ""
					);
/* 				
$of_options[] = array(
					"name"			=> "Borders style",
					"desc"			=> "change the borders style throughout the site. The decorations are vector font icons.",
					"id"			=> "border_icon",
					"std"			=> array(
										'width'		=> '1',
										'style'		=> 'dotted',
										'color'		=> '#adadad',
										'decoration'=> 'deco-icon-ornament_10',
									),
					"type"			=> "border"
				);	

			 
$of_options[] = array(
					"name"			=> "Disable border in side menu /header menu",
					"desc"			=> "remove the border outline on side menu and/or header menu",
					"id"			=> "no_border_sitemenu",
					"std"			=> 0,
					"type"			=> "checkbox"); 
*/	
$of_options[] = array(
					"name"			=> "",
					"desc_html"		=> '<h3>COLOR SETTINGS</h3><p>overall color settings for elements appearing throughout the theme</p>',
					"id"			=> "css_colors_note",
					"std"			=> '',
					"type"			=> "html"
					); 
					
					
$of_options[] = array( 
					"name"			=> "Images hover overlay color",
					"desc"			=> "appears when hovering with cursor over image",
					"id"			=> "item_overlay_color",
					"std"			=> "",
					"type"			=> "color"
					);   				
	
$of_options[] = array(
					"name"			=> "Images hover overlay opacity",
					"desc"			=> "",
					"id"			=> "item_overlay_opacity",
					"std"			=> "30",
					"min"			=> "0",
					"step"			=> "10",
					"max"			=> "100",
					"type"			=> "sliderui" 
					);
$of_options[] = array(
					"name"			=> "",
					"desc_html"		=> '<h4>LINKS</h4>',
					"id"			=> "link",
					"std"			=> '',
					"type"			=> "html"
					); 
	
$of_options[] = array( 
					"name"			=> "Links color",
					"desc"			=> "change overall links color .",
					"id"			=> "links_color",
					"std"			=> "#008cba",
					"type"			=> "color"
					);   				

$of_options[] = array( 
					"name"			=> "Links hover color",
					"desc"			=> "change overall links HOVER color",
					"id"			=> "links_hover_color",
					"std"			=> "#0078a0",
					"type"			=> "color"
					); 
					
$of_options[] = array(
					"name"			=> "",
					"desc_html"		=> '<h4>BUTTONS:</h4>',
					"id"			=> "buttons",
					"std"			=> '',
					"type"			=> "html"
					); 	

$of_options[] = array( 
					"name"			=> "Buttons background color",
					"desc"			=> "change overall background color of buttons.",
					"id"			=> "buttons_bck_color",
					"std"			=> "",
					"type"			=> "color"
					);   	

$of_options[] = array( 
					"name"			=> "Buttons HOVER background color",
					"desc"			=> "change overall background color of buttons hover.",
					"id"			=> "buttons_hover_bck_color",
					"std"			=> "",
					"type"			=> "color"
					);   	

$of_options[] = array( 
					"name"			=> "Buttons font color",
					"desc"			=> "change overall font color of buttons.",
					"id"			=> "buttons_font_color",
					"std"			=> "#fff",
					"type"			=> "color"
					);   	

$of_options[] = array( 
					"name"			=> "Buttons HOVER font color",
					"desc"			=> "change overall  ont color of buttons hover.",
					"id"			=> "buttons_hover_font_color",
					"std"			=> "",
					"type"			=> "color"
					);   	

					
$of_options[] = array(
					"name"			=> "",
					"desc_html"		=> '<h4>SITE AND CONTENT BACKGROUND:</h4>',
					"id"			=> "buttons",
					"std"			=> '',
					"type"			=> "html"
					); 	
					

$of_options[] = array( 
					"name"			=> "Site background",
					"desc_html"		=> "options for site background ( changes will apply to body html tag)",
					"id"			=> "site_background_heading",
					"std"			=> "",
					"type"			=> "html"
					);
			
$of_options[] = array( 
					"name"			=> "Site background color",
					"desc"			=> "change overal background color.",
					"id"			=> "site_back_color",
					"std"			=> "",
					"type"			=> "color"
					);    									

$of_options[] = array(
					"name"			=> "Site background - tiles or uploaded images ?",
					"desc"			=> "Select if you want to use our selection of background tile images, or if you want to upload your own.",
					"id"			=> "site_bg_toggle",
					"std"			=> "none",
					"type"			=> "radio",
					"options"		=> array(
							'default'	=> 'Default background tiles',
							'upload'	=> 'Uploaded background images',
							'none'		=> 'None'
						)
					);					
					
$of_options[] = array(
					"name"			=> "Site background - tiles",
					"desc"			=> "Select a site background tile pattern. Tile pattern can only have fixed back position.<br /><br /><strong>NOTE: The last image is completey transparent, so if you don't want any background tile, select last pattern.</strong>",
					"id"			=> "site_bg_default",
					"fold"			=> "default", /* the radio hook for show */
					"unfold"		=> "site_bg_toggle",/* the radio hook for hide */
					"std"			=> "",
					"type"			=> "tiles",
					"options"		=> $bg_images,
					);
				
				
$of_options[] = array(
					"name"			=> "Site background - upload your  image",
					"desc"			=> "Upload images for site background, or define the URL directly",
					"id"			=> "site_bg_uploaded",
					"fold"			=> "upload", /* the radio hook for show */
					"unfold"		=> "site_bg_toggle",/* the radio hook for hide */
					"std"			=> "",
					"type"			=> "media");	
					
					
$of_options[] = array(
					"name"			=> "Site background - controls ",
					"desc"			=> "<b>If you uploaded your own background image</b>, select how do you want your uploaded image to repeat or if you want to repeat image at all.",
					"id"			=> "site_bg_controls",
					"std" => array(
							'repeat'		=> 'repeat',
							'position'		=> '',
							'attachment'	=> '',
							'size'			=> 'cover'
							),
					"type"			=> "background",
					);    			

					
$of_options[] = array( 
					"name"			=> "Body background",
					"desc_html"		=> "options for body background - main content area between header and footer ( changes will apply to <strong>div#page</strong> html tag )",
					"id"			=> "site_background_heading",
					"std"			=> "",
					"type"			=> "html"
					);					


$of_options[] = array( 
					"name"			=> "Body background color",
					"desc"			=> "change overal background color.",
					"id"			=> "body_back_color",
					"std"			=> "",
					"type"			=> "color"
					); 
					
$of_options[] = array(
					"name"			=> "Body background color opacity",
					"desc"			=> "will apply only if Body background color is applied",
					"id"			=> "body_back_color_opacity",
					"std"			=> "100",
					"min"			=> "0",
					"step"			=> "1",
					"max"			=> "100",
					"type"			=> "sliderui" 
					);
					
$of_options[] = array(
					"name"			=> "Body background - tiles or uploaded images ?",
					"desc"			=> "Select if you want to use our selection of background tile images, or if you want to upload your own.",
					"id"			=> "body_bg_toggle",
					"std"			=> "none",
					"type"			=> "radio",
					"options"		=> array(
							'default'	=> 'Default background tiles',
							'upload'	=> 'Uploaded background images',
							'none'		=> 'None'
						)
					);					
					
$of_options[] = array(
					"name"			=> "Body background - tiles",
					"desc"			=> "Select a body background tile pattern. Tile pattern can only have fixed back position.<br /><br /><strong>NOTE: The last image is completey transparent, so if you don't want any background tile, select last pattern.</strong>",
					"id"			=> "body_bg_default",
					"fold"			=> "default", /* the radio hook for show */
					"unfold"		=> "body_bg_toggle",/* the radio hook for hide */
					"std"			=> "",
					"type"			=> "tiles",
					"options"		=> $bg_images,
					);
				
				
$of_options[] = array(
					"name"			=> "Body background - upload your  image",
					"desc"			=> "Upload images for body background, or define the URL directly",
					"id"			=> "body_bg_uploaded",
					"fold"			=> "upload", /* the radio hook for show */
					"unfold"		=> "body_bg_toggle",/* the radio hook for hide */
					"std"			=> "",
					"type"			=> "media");	
					
					
$of_options[] = array(
					"name"			=> "Body background - controls ",
					"desc"			=> "<b>If you uploaded your own background image</b>, select how do you want your uploaded image to repeat or if you want to repeat image at all.",
					"id"			=> "body_bg_controls",
					"std" => array(
							//'color'			=> '',
							'repeat'		=> 'repeat',
							'position'		=> '',
							'attachment'	=> '',
							'size'		=> ''
							),
					"type"			=> "background",
					);    			

					
					
												   
$of_options[] = array(
					"name"			=> "Custom CSS",
					"desc"			=> "If you want additionally to customize theme styles, you can enter custom css code in this textfiled",
					"id"			=> "custom_css",
					"std"			=> "",
					"type"			=> "textarea"
					);    
						
	
					
//============================= HEADER TAB ================================
				
$of_options[] = array(
					"name"			=> "Header / Side menu",
					"type"			=> "heading",
					"std"			=> ""
					);
					
$of_options[] = array(
					"name"			=> "",
					"desc_html"		=> '<h3>HEADER / SIDE MENU LAYOUT AND STYLES</h3><p>Choose the layout and style settings for header including elements formatting and custom css settings</p>',
					"id"			=> "header_note",
					"std"			=> '',
					"type"			=> "html"
					); 				
$of_options[] = array(
					"name"			=> "Header or Side menu version (layout orientation)",
					"desc"			=> "Choose orientation of header and menu - logo, title, navigation, search and breadcrumb ",
					"id"			=> "orientation",
					"std"			=> "horizontal",
					"type"			=> "radio",
					"options"		=> array(
							'horizontal'	=> 'Header horizontal',
							'vertical'		=> 'Side menu (customizable)'
						)
					);
					
$of_options[] = array(
					"name"			=> "Predefined header layouts",
					"desc"			=> '',
					"fold"			=> "horizontal", /* the radio hook for show */
					"unfold"		=> "orientation",/* the radio hook for hide */
					"id"			=> "predefined_headers",
					"std"			=> "01",
					"type"			=> "images",
					"options"		=> array(
						'01'		=> $images_url . 'header_01.png',
						'02'		=> $images_url . 'header_02.png',
						'03'		=> $images_url . 'header_03.png',
						'04'		=> $images_url . 'header_04.png',
						'05'		=> $images_url . 'header_05.png',
						'simple'	=> $images_url . 'header_simple.png'	
						)
					);			

$of_options[] = array(
					"name"			=> "Minimalistic header - sidebar animations (no IE9)",
					"desc"			=> "",
					"fold"			=> 'simple', /* the radio hook for show */
					"unfold"		=> "predefined_headers",/* the radio hook for hide */
					"id"			=> "min_header_anim",
					"std"			=> "1",
					"type"			=> "select",
					"options"		=> array(
							'1'		=> 'Slide in on top',
							'2'		=> 'Reveal',
							'3'		=> 'Push',
							'4'		=> 'Slide along',
							'5'		=> 'Reverse slide out',
							//'6'		=> 'Rotate pusher',
							'7'		=> '3D rotate in',
							'8'		=> '3D rotate out',
							'9'		=> 'Scale down pusher',
							'10'	=> 'Scale Up',
							'11'	=> 'Scale &amp; rotate pusher',
							'12'	=> 'Open door',
							'13'	=> 'Fall down',
							'14'	=> 'Delayed 3D Rotate'
							
						)
					);					
			
$of_options[] = array(
					"name"			=> "Side menu blocks (default)",
					"desc"			=> "Rearrange the latest section by <strong>dragging and dropping </strong> blocks and  <strong>resize </strong> them.",
					"id"			=> "default_header_blocks",
					"resizable"		=> false,
					"fold"			=> "vertical", /* the radio hook for show */
					"unfold"		=> "orientation",/* the radio hook for hide */
					"std"			=> apply_filters('sequoia_vertical_header_blocks',''),
					"type"			=> "sorter"
				);

			
$of_options[] = array(
					"name"			=> "Logo/title width (height is auto)",
					"desc"			=> "change the width of logo image or title (set in WP settings) in header. Insert <strong>only the number</strong>, units as hardcoded in pixels (px)",
					"id"			=> "logo_width",
					"fold"			=> "vertical", /* the radio hook for show */
					"unfold"		=> "orientation",/* the radio hook for hide */
					"mod"			=> "mini",
					"std"			=> '',
					"type"			=> "text"
					); 					

$of_options[] = array(
					"name"			=> "Logo/title height (width is auto)",
					"desc"			=> "change the height of logo image or title (set in WP settings) in header. Insert <strong>only the number</strong>, units as hardcoded in pixels (px)",
					"id"			=> "logo_height",
					"fold"			=> "horizontal", /* the radio hook for show */
					"unfold"		=> "orientation",/* the radio hook for hide */
					"mod"			=> "mini",
					"std"			=> '',
					"type"			=> "text"
					); 						
					
					
$of_options[] = array(
					"name"			=> "",
					"desc_html"		=> '<strong>TITLE SETTINGS (if logo is disabled)',
					"fold"			=> "vertical", /* the radio hook for show */
					"unfold"		=> "orientation",/* the radio hook for hide */
					"id"			=> "logo_title_expl",
					"std"			=> '',
					"type"			=> "html"
					); 
			
			
$of_options[] = array(
					"name"			=> "Title font size (percentage)",
					"desc"			=> "site title font is 2em in size by default. Change font size by percentage - minimum is 50%, maximum is 300%",
					"id"			=> "title_font_size",
					"std"			=> "100",
					"min"			=> "50",
					"step"			=> "10",
					"max"			=> "300",
					"type"			=> "sliderui" 
					);
					
$of_options[] = array(
					"name"			=> "Title word breaking",
					"desc"			=> "if your site title has long words and you need to fit it into sidebar",
					"id"			=> "title_break_word",
					"std"			=> 0,
					"on"			=> "Break title words",
					"off"			=> "Breaks after words",
					"folds"			=> 0,
					"type"			=> "switch"
					);		
					
					
				
$of_options[] = array(
					"name"			=> "",
					"desc_html"		=> '<strong>IMPORTANT NOTE: Logo width applies for side menu (vertical layout) and it\'s limited with maximum width of 300 pixels due to layout limitations .</strong>',
					"fold"			=> "vertical", /* the radio hook for show */
					"unfold"		=> "orientation",/* the radio hook for hide */
					"id"			=> "logo_width_expl",
					"std"			=> '',
					"type"			=> "html"
					); 

			

$of_options[] = array(
					"name"			=> "Header Custom CSS",
					"desc"			=> "horizontal header will probably need some custom css for tweaking. Some css knowledge is required to know how to handle css cascading overrides.",
					"id"			=> "header_custom_css",
					"std"			=> "",
					"fold"			=> "horizontal", /* the radio hook for show */
					"unfold"		=> "orientation",/* the radio hook for hide */
					"type"			=> "textarea"
					); 			

				
$of_options[] = array(
					"name"			=> "Header/menu blocks for MOBILE DEVICES",
					"desc"			=> "Set the visibility of blocks for mobile devices (under 1024px of horizontal resoultion).",
					"id"			=> "mobile_header_blocks",
					"resizable"		=> false,
					"std"			=> apply_filters('sequoia_mobile_header_blocks',''),
					"type"			=> "sorter"
				);

$of_options[] = array(
					"name"			=> "<h4>HEADER / SIDE MENU STYLING</h4>",
					"desc_html"		=> '',
					"id"			=> "header-styling",
					"std"			=> '',
					"type"			=> "html"
					); 
					
$of_options[] = array( 
					"name"			=> "Header / Side menu background color",
					"desc"			=> "change header background color (applies to menus background, too).",
					"id"			=> "header_back_color",
					"std"			=> "",
					"type"			=> "color"
					);  
					
$of_options[] = array(
					"name"			=> "Header background color opacity",
					"desc"			=> "",
					"id"			=> "header_back_opacity",
					"std"			=> "100",
					"min"			=> "0",
					"step"			=> "1",
					"max"			=> "100",
					"type"			=> "sliderui" 
					);
						
$of_options[] = array(
					"name"			=> "Side menu background color opacity",
					"desc"			=> "",
					"id"			=> "sidemenu_back_opacity",
					"std"			=> "100",
					"min"			=> "0",
					"step"			=> "1",
					"max"			=> "100",
					"type"			=> "sliderui" 
					);

$of_options[] = array(
					"name"			=> "",
					"desc_html"		=> '<strong>IMPORTANT NOTE: header back color applies to header and menus ( and sidemenu, too). Opacities are different for header and side menu.</strong>',
					"id"			=> "header_font_color_expl",
					"std"			=> '',
					"type"			=> "html"
					); 
					
				
$of_options[] = array( 
					"name"			=> "Header / Side menu font color",
					"desc"			=> "change font colors in header.",
					"id"			=> "header_font_color",
					"std"			=> "",
					"type"			=> "color"
					);  
					
$of_options[] = array(
					"name"			=> "",
					"desc_html"		=> '<strong>IMPORTANT NOTE: header font color applies to any text that is not link</strong>',
					"id"			=> "header_font_color_expl",
					"std"			=> '',
					"type"			=> "html"
					); 
					
$of_options[] = array( 
					"name"			=> "Header / Side menu links color (primary)",
					"desc"			=> "change header links color (font and button border).",
					"id"			=> "header_links_color",
					"std"			=> "",
					"type"			=> "color"
					);   				
			
$of_options[] = array( 
					"name"			=> "Header / Side menu links hover color (secondary)",
					"desc"			=> "change header hover color (font and button border).",
					"id"			=> "header_links_hover_color",
					"std"			=> "",
					"type"			=> "color"
					);   				
									
		
//=================== HEADER INPUT -  ================================ 

$of_options[] = array(
					"name"			=> "Header info",
					"id"			=> "TAB - header info",
					"type"			=> "heading",
					"std"			=> ""
					);
					
$of_options[] = array(
					"name"			=> "",
					"desc_html"		=> '<h3>HEADER INFO</h3><p>This is the options section with information input fields - contact info, social icons</p>',
					"id"			=> "header_note",
					"std"			=> '',
					"type"			=> "html"
					); 					
					
$of_options[] = array( 
					"name"			=> "Contact info/ Social links",
					"desc"			=> "",
					"id"			=> "topbar_info",
					"std"			=> array ( 
						
						array ( 
							'order'		=> 0, 
							'title'		=> '00 123 546',
							'link'		=> '',
							'icon'		=> "icon-phone",
							'toggle'	=> 0,
							'description' => ''
						),
						array ( 
							'order'		=> 1, 
							'title'		=> '55 446 5476',
							'link'		=> '',
							'icon'		=> "icon-mobile",
							'toggle'	=> 0,
							'description' => ''
						),
						array ( 
							'order'		=> 2, 
							'title'		=> 'Facebook',
							'link'		=> 'http://facebook.com/aligatorstudio',
							'icon'		=> "icon-facebook",
							'toggle'	=> 1,
							'description' => ''
						),
						array ( 
							'order'		=> 3, 
							'title'		=> 'Twitter',
							'link'		=> 'http://twitter.com/aligatorstudio',
							'icon'		=> "icon-twitter",
							'toggle'	=> 1,
							'description' => ''
						),
								
								
								),
					"type"			=> "icons");

					


					
//=================== FOOTER TAB ================================ 

$of_options[] = array(
					"name"			=> "Footer Settings",
					"id"			=> "TAB - footer settings",
					"type"			=> "heading",
					"std"			=> ""
					);
					
$of_options[] = array( 
					"name"			=> "Footer font color",
					"desc"			=> "change font colors in footer.",
					"id"			=> "footer_font_color",
					"std"			=> "",
					"type"			=> "color"
					);   				

					
$of_options[] = array( 
					"name"			=> "Footer links and buttons color (primary)",
					"desc"			=> "change overal color (font and button border).",
					"id"			=> "footer_links_color",
					"std"			=> "",
					"type"			=> "color"
					);   				
			
$of_options[] = array( 
					"name"			=> "Footer links and buttons hover color (secondary)",
					"desc"			=> "change overal color (font and button border).",
					"id"			=> "footer_links_hover_color",
					"std"			=> "",
					"type"			=> "color"
					);   				
									
			
$of_options[] = array( 
					"name"			=> "Footer background color",
					"desc"			=> "change overal background color.",
					"id"			=> "footer_back_color",
					"std"			=> "",
					"type"			=> "color"
					);  

$of_options[] = array(
					"name"			=> "Footer background color opacity",
					"desc"			=> "",
					"id"			=> "footer_back_opacity",
					"std"			=> "80",
					"min"			=> "1",
					"step"			=> "1",
					"max"			=> "100",
					"type"			=> "sliderui" 
					);
					
$of_options[] = array(
					"name"			=> "Footer Credits text",
                    "desc"			=> "You can enter custom footer text here. If you want default footer text (&copy; - site title), just leave the textfield blank.",
                    "id"			=> "footer_text",
                    "std"			=> "",
                    "type"			=> "textarea"
					);			
							
	
/******************************** HOME PAGE TAB ***************************************/
					
					
$of_options[] = array(
					"name"			=> "Home Settings",
					"id"			=> "TAB - home settings",
					"type"			=> "heading",
					"std"			=> ""
					);

  					                                               
       
$of_options[] = array(
					"name"			=> "Blog home page title",
					"desc"			=> "If <strong>Your latest posts</strong> is chosen for site home page, choose if you want to display page title ( the site title with site description - set it up in <strong>Settings - General</strong> ).",
					"id"			=> "index_title",
					"std"			=> 1,
					"type"			=> "switch"
        );
		
$of_options[] = array(
					"name"			=> "Blog home page header background image",
					"desc"			=> 'If "Your latest posts" is chosen for site home page, choose if you want to display header backgroud image',
					"id"			=> "index_title_bcktoggle",
					"std"			=> 1,
					"folds"			=> 1,
					"type"			=> "switch"
        );
$of_options[] = array(
					"name"			=> "Upload blog home page header background image",
					"desc"			=> "upload image for home page blog title background (optional)",
					"id"			=> "index_title_backimg",
					"fold"			=> "index_title_bcktoggle", /* the folding hook */
					"std"			=> get_template_directory_uri(). '/img/header-portfolio.jpg',
					"type"			=> "media");
					

					
				

/****************************** BLOG SETTINGS TAB *************************************/					
					
					
$of_options[] = array(
					"name"			=> "Blog Settings",
					"id"			=> "TAB - blog settings",
                    "type"			=> "heading",
					"std"			=> ""
					);
					
$of_options[] = array(
					"name"			=> "Featured image size (in px)",
					"desc"			=> "set blog featured image size - if left empty, the predefined (registered) image size will be used",
					"id"			=> "blog_fetured_img_size",
					"mod"			=> "mini",
					"multi"			=> true, // if true - in 'std' goes array
					"std" 			=>  array(
									"Width"		=> '',
									"Height"	=> '',

								),
					"type"			=> "text"
					);
					
$of_options[] = array(
					"name"			=> "<strong>Single blog page</strong> title background image (featured) ?",
					"desc"			=> 'choice to have image background on single blog. Use featured image.<br /><br /><strong>IMPORTANT NOTE: if no featured image is set and this option is ON, the blog archive background image will be used</strong>',
					"id"			=> "single_blog_title_bcktoggle",
					"std"			=> 1,
					"folds"			=> 1,
					"on" 			=>"Show",
					"off"			=> "Hide",
					"type"			=> "switch"
				);
				
				
$of_options[] = array(
					"name"			=> "Blog archive title background image ?",
					"desc"			=> 'choice to have image background on blog archive and taxonomies pages. For single blog pages, use featured image.',
					"id"			=> "blog_title_bcktoggle",
					"std"			=> 1,
					"folds"			=> 1,
					"on"			=> "Show",
					"off"			=> "Hide",
					"type"			=> "switch"
				);	


					
$of_options[] = array(
					"name"			=> "Upload blog archive title background image",
					"desc"			=> "upload image for blog/taxnomies titles background (optional)",
					"id"			=> "blog_title_backimg",
					"fold"			=> "blog_title_bcktoggle", /* the folding hook */
					"std"			=> get_template_directory_uri(). '/img/header-archive.jpg',
					"type"			=> "media");


				
$of_options[] = array(
					"name"			=> "Blog CATEGORIES/TAGS title background image ?",
					"desc"			=> 'choice to have image background on blog categories pages. For single blog pages, use featured image.',
					"id"			=> "blog_cat_title_bcktoggle",
					"std"			=> 1,
					"folds"			=> 1,
					"on"			=> "Show",
					"off"			=> "Hide",
					"type"			=> "switch"
				);	
					
					
$of_options[] = array(
					"name"			=> "Upload blog CATEGORIES/TAGS title background image",
					"desc"			=> "upload image for blog categories titles background (optional)",
					"id"			=> "blog_cat_title_backimg",
					"fold"			=> "blog_cat_title_bcktoggle", /* the folding hook */
					"std"			=> get_template_directory_uri(). '/img/header-cats.jpg',
					"type"			=> "media"
					);
				
$of_options[] = array(
					"name"			=> "Blog AUTHOR pages title background image ?",
					"desc"			=> 'choice to have image background on blog author pages. For single blog pages, use featured image.',
					"id"			=> "blog_author_title_bcktoggle",
					"std"			=> 1,
					"folds"			=> 1,
					"on"			=> "Show",
					"off"			=> "Hide",
					"type"			=> "switch"
					);
					
					
$of_options[] = array(
					"name"			=> "Upload blog AUTHOR pages title background image",
					"desc"			=> "upload image for blog author pages titles background (optional)",
					"id"			=> "blog_author_title_backimg",
					"fold"			=> "blog_author_title_bcktoggle", /* the folding hook */
					"std"			=> get_template_directory_uri(). '/img/header-author.jpg',
					"type"			=> "media"
					);

					
$of_options[] = array(
					"name"			=> "BLOG AND PORTFOLIO POST META AND POST FORMAT SETTINGS",
					"desc_html"			=> '<p><strong></strong><br />Settings bellow are appliable to both blog posts and portfolio items - in the single view or archive view. Page builder settings are not affected with this settings.</p>',
					"id"			=> "blog_portfolio_meta_format",
					"std"			=> '',
					"type"			=> "html"
					); 	
					
					
					
$of_options[] = array(
					"name"			=> "Post meta settings",
					"desc"			=> "turn on/off post meta, date and author, categories and tags, comments, and permalink boxes.",
					"id"			=> "post_meta",
					"std"			=> array('date_author','categories_tags','comments', 'link'),
				  	"type"			=> "multicheck",
					"options"		=> array(
									'date_author'		=> 'Date and author',
									'categories_tags'	=> 'Post categories and tags',
									'comments'			=> 'Comments count',
								)
					);	

					
$of_options[] = array(
					"name"			=> "Post date format",
					"desc"			=> "Which date format would you like to use: day, month, year, or different. Type in the order the <strong>d</strong> or <strong>D</strong> for day, <strong>m</strong> or <strong>M</strong> for month, <strong>Y</strong> for year ...",
					"id"			=> "post_date_format",
					"std"			=> "d M Y",
					"type"			=> "text"
					);



$of_options[] = array(
					"name"			=> "Widget title icons ?",
					"desc"			=> 'sidebar (and other widget areas) has <em>iconized </em>titles. To remove icons, toggle this switch. NOTE: Icons are applied only to default WP widgets, WooCommerce widgets and theme widgets. Third party widgets are icon-free',
					"id"			=> "default_widget_icons",
					"std"			=> 1,
					"on"			=> "Show widget icons",
					"off"			=> "Hide widget icons",
					"type"			=> "switch"
					);

$of_options[] = array(
					"name"			=> "Archive/index page viewport animation",
					"desc"			=> "choose the product items animation when archive page loads and when post enters browser viewport. The posts will consecutevly load, depending on their order of appearance.<br> NOTE: this setting will apply site blog index",
					"id"			=> "post_enter_anim_archive",
					"std"			=> "fadeIn",
					"type"			=> "select",
					"options"		=> animations_array()
					);

$of_options[] = array(
					"name"			=> "Taxonomies page viewport animation",
					"desc"			=> "choose the product items animation when category/ tag/ author page loads and when post enters browser viewport. The posts will consecutevly load, depending on their order of appearance.",
					"id"			=> "post_enter_anim_tax",
					"std"			=> "fadeIn",
					"type"			=> "select",
					"options"		=> animations_array()
					);

					
/****************************** PORTFOLIO SETTINGS TAB *************************************/					
					
					
$of_options[] = array(
					"name"			=> "Portfolio Settings",
					"id"			=> "TAB - blog settings",
                    "type"			=> "heading",
					"std"			=> ""
					);

					
$of_options[] = array(  
					"name"			=> "<strong>Single portfolio page</strong> title background image (featured) ?",
					"desc"			=> 'choice to have image background on single portfolio item. Use featured image.<br /><br /><strong>IMPORTANT NOTE: if no featured image is set and this option is ON, the portfolio archive background image will be used</strong>',
					"id"			=> "single_portfolio_title_bcktoggle",
					"std"			=> 1,
					"folds"			=> 1,
					"on"			=> "Show",
					"off"			=> "Hide",
					"type"			=> "switch"
				);
				
				
$of_options[] = array(
					"name"			=> "Portfolio archive/taxonomies title background image ?",
					"desc"			=> 'choice to have image background on portfolio archive and taxonomies pages. For single portfolio pages, use portfolio item featured image.',
					"id"			=> "portfolio_title_bcktoggle",
					"std"			=> 1,
					"folds"			=> 1,
					"on"			=> "Show",
					"off"			=> "Hide",
					"type"			=> "switch"
				);	
					
					
$of_options[] = array(
					"name"			=> "Set portfolio archive/taxonomies title background image",
					"desc"			=> "upload image for portfolio archive or taxonomies (portfolio categories and tags) titles background (optional)",
					"id"			=> "portfolio_title_backimg",
					"fold"			=> "portfolio_title_bcktoggle", /* the folding hook */
					"std"			=> get_template_directory_uri(). '/img/header-portfolio.jpg',
					"type"			=> "media");


		

//=================== BACKUP TAB ================================ 					
					
$of_options[] = array(
					"name"			=> "BACKUP",
					"id"			=> "TAB - backup",
					"type"			=> "heading",
					"std"			=> ""
					);

					
$of_options[] = array(
					"name"			=>"Backup theme options",
					"id"			=>"backup_options",
					"std"			=> "",
					"type"			=>"backup",
					"options"		=> "Please, use this to save theme options in case you want to allow upgrading theme to new version."
					
				);
				
$of_options[] = array(
					"name"			=> "Backup help",
					"desc"			=> "",
					"id"			=> "backup_help",
					"std"			=> "<h3>HELP:</h3>This utility backups <b>SAVED</b> theme options.<br />Remember, when restoring backuped options: after you click on <b>'Restore options'</b>, you must click on <b>'Save All Changes'</b> to apply restored options. So, in short:<ul><li><b> &rarr; to backup </b>- first save, then backup</li><li><b>&rarr; to restore </b>- first restore, then save</li></ul>",
					"icon"			=> true,
					"type"			=> "info");	
					
$of_options[] = array(
					"name"			=> "Transfer Theme Options Data",
                    "id"			=> "of_transfer",
                    "std"			=> "",
                    "type"			=> "transfer",
					"desc"			=> 'You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options".
						',
					);

					
	} // end function of_options
}
?>