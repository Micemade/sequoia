<?php
/**
 * AJAX LOAD STUFF.
 * - load ajax url
 * - loading items from product categories ( has special product features).
 * - loading items from posts, portfolio or categories ( has common features for selected post types).
 */
function sequoia_ajax_url_var() {
	echo '<script type="text/javascript">var sequoia_ajaxurl = "'. admin_url("admin-ajax.php") .'"</script>';
}
add_action('wp_head', 'sequoia_ajax_url_var' );
/**
 *   PRODUCT CATEGORIES:
 *  
 */
add_action( 'wp_ajax_nopriv_load-filter', 'ajax_load_product_categories' ); // for NOT logged in users
add_action( 'wp_ajax_load-filter', 'ajax_load_product_categories' );// for logged in users

function ajax_load_product_categories () {
	
	global $post, $sequoia_woo_is_active, $sequoia_wishlist_is_active, $product, $woocommerce_loop, $wp_query, $woocommerce;
	
	if( $sequoia_woo_is_active ) {
	
	// get variables using $_POST
	$tax_term		= $_POST[ 'termID' ];
	$taxonomy		= $_POST[ 'tax' ];
	$post_type		= $_POST[ 'post_type' ];
	$total_items	= $_POST[ 'total_items' ];
	$filters		= $_POST[ 'filters' ];
	$img_format		= $_POST[ 'img_format' ];
	$shop_quick		= $_POST[ 'shop_quick' ];
	$shop_buy_action= $_POST[ 'shop_buy_action' ];
	$shop_wishlist	= $_POST[ 'shop_wishlist' ];
	$enter_anim		= $_POST[ 'enter_anim' ];
	$no_slider_grid	= $_POST[ 'no_slider_grid' ];
	$zoom_button	= $_POST[ 'zoom_button' ];
	$link_button	= $_POST[ 'link_button' ];
	//
	//
	// PRODUCT FILTERS:
	$order_rand	= false;
	if ( $filters == 'featured' ){
		
		$args_filters = array( 
			'meta_key' => '_featured',
			'meta_value' => 'yes'
		);
		remove_filter( 'posts_clauses',  array( $woocommerce->query, 'order_by_rating_post_clauses' ) );
		
	}elseif( $filters == 'best_sellers' ){
		
		$args_filters = array( 
			'meta_key' 	 => 'total_sales'
		);
		remove_filter( 'posts_clauses',  array( $woocommerce->query, 'order_by_rating_post_clauses' ) );
	
	}elseif( $filters == 'best_rated' ){
		
		$args_filters = array();
		add_filter( 'posts_clauses',  array( $woocommerce->query, 'order_by_rating_post_clauses' ) );
		
	}elseif( $filters == 'latest' ){
		
		$args_filters = array();
		remove_filter( 'posts_clauses',  array( $woocommerce->query, 'order_by_rating_post_clauses' ) );
	
	}elseif( $filters == 'random' ){
		
		$order_rand	= true;
		$args_filters = array();
		remove_filter( 'posts_clauses',  array( $woocommerce->query, 'order_by_rating_post_clauses' ) );
	}
	//
	
	if( !empty($tax_term) ) {

		$tax_term = explode(",", $tax_term); // back to array
		
		$tax_filter_args = array('tax_query' => array(
							array(
								'taxonomy' => $taxonomy,
								'field' => 'slug', // can be 'slug' too
								'operator' => 'IN', // NOT IN to exclude
								'terms' => $tax_term
							)
						)
					);
	}else{
		$tax_filter_args = array();
	}
	$main_args = array(
		'no_found_rows' => 1,
		'post_status' => 'publish',
		'post_type' => $post_type,
		'post_parent' => 0,
		'suppress_filters' => false,
		'orderby'     => $order_rand ? 'rand menu_order date' : 'menu_order date',
		'order'       => 'ASC',
		'numberposts' => $total_items
	);
	$all_args = array_merge( $main_args, $args_filters, $tax_filter_args );
	
	$content = get_posts($all_args);
		
	ob_start ();

	$i = 1;
	
	if( count( $content ) == 0 ) {
	
		echo '<h4 class="no-category-item">'.__('No product was found for this category.','sequoia').'</h4>';
		
	} 
	
	
	foreach ( $content as $post ) {
		setup_postdata( $post );
		
		global $product, $yith_wcwl;
		
		if( defined('WPML_ON') ) { // if WPML plugin is active
			$id	= icl_object_id( get_the_ID(), 'product', false, ICL_LANGUAGE_CODE ); 
			$lang_code	= ICL_LANGUAGE_CODE;
		}else{
			$id	= get_the_ID();
			$lang_code	= '';
		}
		$link = get_permalink($id);
		
		
		// DATA for back image
		$attachment_ids = $product->get_gallery_attachment_ids();
		if ( $attachment_ids ) {
			$image_url = wp_get_attachment_image_src( $attachment_ids[0], 'large'  );
			$img_url = $image_url[0];
			/* // IMAGE SIZES:
			$imgSizes = all_image_sizes(); // as custom fuction
			$img_width = $imgSizes[$img_format]['width'];
			$img_height = $imgSizes[$img_format]['height'];
			*/
		}
		// end DATA
		
		$prod_title = '<h4 class="prod-title"><a href="'. $link .'" title="'.esc_attr(get_the_title()).'"> ' . esc_attr(get_the_title()) .'</a></h4>';
		?>	
		
		<div class="column item <?php echo $no_slider_grid; ?><?php echo ($enter_anim != 'none') ? ' to-anim' : '';  ?>" data-i="<?php echo $i; ?>">

			<?php echo (!$zoom_button && !$link_button) ? '<a href="'.$link.'" title="'. esc_attr(get_the_title()) .'">' : ''; ?>
			
			<div class="item-img">
				
				<div class="front">
												
					<?php function_exists('woocommerce_show_product_loop_sale_flash') ? woocommerce_show_product_loop_sale_flash() : '';
					
					echo as_image( $img_format ); ?>
				
				</div>
				
				<div class="back">
				
					<div class="item-overlay"></div>
					<?php 
					$params = array( 'width' => $img_width, 'height' => $img_height );
					if ( $attachment_ids ) {
						if( $img_width && $img_height ) {
								$params = array( 'width' => $img_width, 'height' => $img_height );
								echo '<img src="'. bfi_thumb( $img_url, $params ).'" alt="'. esc_attr(get_the_title()) .'" class="back-image" />';									
							}else{
								echo wp_get_attachment_image( $attachment_ids[0], $img_format );
							}
					}else{
						echo as_image( $img_format );
					}
						
					echo '<div class="back-buttons">';
													
					echo $zoom_button ? '<a href="'. as_get_full_img_url() .'" class="button tiny magnificpopup mfp-image"  title="'.esc_attr(strip_tags(get_the_title())).'"><span class="icon icon-zoom-in" aria-hidden="true"></span></a>' : null;
							
					echo $link_button ? '<a href="'.$link.'" class="button tiny" title="'. esc_attr(get_the_title()) .'"><span class="icon icon-link" aria-hidden="true"></span></a>' : null;
					
					echo '</div>';
					
					?>
				
				</div>
				
			</div>
			
			<?php echo (!$zoom_button && !$link_button) ? '</a>' : ''; ?>
			
			<div class="item-data">
					
				<div class="table">
				
					<div class="tablerow">	
					
					<?php
					if( $shop_quick ) {
						echo '<div class="item-buttons-holder tablecell">';
						echo '<a href="#qv-holder" class="quick-view tip-top"   title="'.__('Quick view','sequoia').' - '. esc_attr(strip_tags(get_the_title())) .'" data-id="'.$id.'" data-lang="'. $lang_code .'" data-tooltip><span class="icon-eye"></span></a>';
						echo '</div>'; // tablecell
						
						if ( !wp_script_is( 'wc-add-to-cart-variation', 'enqueued' )) {
					
							wp_register_script( 'wc-add-to-cart-variation', WP_PLUGIN_DIR . '/woocommerce/assets/frontend/add-to-cart-variation.min.js');
							wp_enqueue_script( 'wc-add-to-cart-variation' );
						}
						
					}
					
					if( $shop_buy_action ) {
						echo '<div class="item-buttons-holder tablecell">';
							do_action( 'woocommerce_after_shop_loop_item' );
						echo '</div>'; // tablecell
					}
					
					if( $sequoia_wishlist_is_active && $shop_wishlist ) {
						echo '<div class="item-buttons-holder tablecell">';
							do_action('as_wishlist_button');
						echo '</div>'; // tablecell
					}
					
					//
					?>
				
					</div>
				
					<?php //function_exists('woocommerce_template_loop_rating') ? woocommerce_template_loop_rating() : ''; ?>

				</div>
				
				<?php 
				// if all buttons disabled
				$no_buttons =( !$shop_quick && !$shop_buy_action && !$shop_wishlist ) ?  true : false;
				
				echo $no_buttons ? '<div class="no-buttons">' : null;
				
				echo $prod_title;
				
				woocommerce_template_loop_price();
				
				echo $no_buttons ? '</div>' : null;
				?>
				
			
			</div><!-- .item-data -->
			
			<div class="clearfix"></div>	
		
		</div>
						
		<?php 
		$i++;
	} // END foreach
	?>
	
	<script>
	(function( $ ){
		var startMagnificPopupAjax = window.magnificPopupSequoia();
		
	})( jQuery );
	</script>
	
	<?php 
	/* reset, clean buffer and respond with content */
	wp_reset_postdata();
	$response = ob_get_contents();
	ob_end_clean();

	echo $response;
	die(1);

	}else{
		echo '<h5 class="no-woo-notice">' . __('AJAX PRODUCTS BLOCK DISABLED.<br> Sorry, it seems like WooCommerce is not active. Please install and activate last version of WooCommerce.','sequoia') . '</h5>';
			return;
	} // if $sequoia_woo_is_active
	
}
//
//
/** 
 *	POSTS, PORTFOLIO or PRODUCT CATEGORIES.
 *
 *	primarily for posts and portfolios, but can be used for products (product image gallery?)
 */
add_action( 'wp_ajax_nopriv_load-filter2', 'ajax_load_cat_posts' );// for NOT logged in users
add_action( 'wp_ajax_load-filter2', 'ajax_load_cat_posts' );// for logged in users

function ajax_load_cat_posts () {
	
	global $post;
	
	// get all variables using $_POST
	$block_id			= $_POST[ 'block_id' ];
	$tax_term			= $_POST[ 'termID' ];
	$taxonomy			= $_POST[ 'tax' ];
	$post_type			= $_POST[ 'post_type' ];
	$img_format			= $_POST[ 'img_format' ];
	$tax_menu_style		= $_POST[ 'tax_menu_style' ];
	$custom_img_width	= $_POST[ 'custom_img_width' ];
	$custom_img_height	= $_POST[ 'custom_img_height' ];
	$total_items		= $_POST[ 'total_items' ];
	$only_featured		= $_POST[ 'only_featured' ];
	$enter_anim			= $_POST[ 'enter_anim' ];
	$no_slider_grid		= $_POST[ 'no_slider_grid' ];
	$zoom_button		= $_POST[ 'zoom_button' ];
	$link_button		= $_POST[ 'link_button' ];

	//
	$sticky_array = get_option( 'sticky_posts' );
	$total_items = $total_items ? $total_items : -1;
	//
	/*
	 *	IF POSTS, PORTFOLIOS OR PRODUCTS SHOULD BE ONLY FEATURED (STICKY)
	 *
	 */
	if ( $post_type == 'post' && $only_featured ) {
		$args_only_featured = array('post__in' => $sticky_array);
	}elseif ( $post_type == 'portfolio' && $only_featured ){
		$args_only_featured = array( 
			'meta_key' => 'as_featured_item',
			'meta_value' => 1
		);
	}elseif ( $post_type == 'product' && $only_featured ){
		$args_only_featured = array( 
			'meta_key' => '_featured',
			'meta_value' => 'yes'
		);
	}else{
		$args_only_featured = array();
	}
	
	if( !empty($tax_term) ) {

		$tax_term = explode(",", $tax_term); // back to array
		
		$tax_filter_args = array('tax_query' => array(
							array(
								'taxonomy' => $taxonomy,
								'field' => 'slug', // can be 'slug' too
								'operator' => 'IN', // NOT IN to exclude
								'terms' => $tax_term
							)
						)
					);
	}else{
		$tax_filter_args = array();
	}
	$main_args = array(
		'no_found_rows' => 1,
		'post_status' => 'publish',
		'post_type' => $post_type,
		'post_parent' => 0,
		'suppress_filters' => false,
		'orderby'     => 'post_date',
		'order'       => 'DESC',
		'numberposts' => $total_items
	);
	$all_args = array_merge( $main_args, $args_only_featured, $tax_filter_args );
	
	$content = get_posts($all_args);
	
	// IF CUSTOM IMAGE SIZE ( both width and height required )
	if( $custom_img_width && $custom_img_height ) {
		$img_width = $custom_img_width ? $custom_img_width : 450;
		$img_height = $custom_img_height ? $custom_img_height : 300;
	}else{
		$img_width	= '';
		$img_height = '';
	}	
		
	ob_start ();
	
	$i = 1;
	
	foreach ( $content as $post ) {
			
		setup_postdata( $post );
		
		if( defined('WPML_ON') ) { // if WPML plugin is active
			$post_id	= icl_object_id( get_the_ID(), get_post_type(), false, ICL_LANGUAGE_CODE ); 
			$lang_code	= ICL_LANGUAGE_CODE;
		}else{
			$post_id	= get_the_ID();
			$lang_code	= '';
		}
		
		$link			= esc_attr( get_permalink($post_id) );
				$post_title		= '<h4><a href="'. $link.'" title="'. esc_attr(get_the_title()).'">'. esc_html( strip_tags(get_the_title()) ) .'</a></h4>';
				$post_format	= get_post_format();
				$pP_rel			= '';
				
				// custom AS function in inc/functions dir
				$post_formats 	= as_post_formats_media( $post_id, $block_id, $img_format, $img_width, $img_height );
				
				$img_url			= $post_formats['img_url'];
				$image_output		= $post_formats['image_output'];
				$pP_rel				= $post_formats['pP_rel'];
				$img_urls_gallery	= $post_formats['img_urls_gallery'];
				$quote_html			= $post_formats['quote_html'];
		?>
			
		<div class="column item <?php echo $no_slider_grid; ?><?php echo ($enter_anim != 'none') ? ' to-anim' : '';  ?>" data-i="<?php echo $i; ?>">
			
			<?php echo (!$zoom_button && !$link_button) ? '<a href="'. $link.'" title="'. esc_attr(strip_tags(get_the_title())).'">' : ''; ?>
			
			<div class="item-img">
			
				<div class="front">
					
					<?php echo $image_output ; ?>
					
				</div>
				
				<div class="back">
				
					<?php echo $image_output; ?>
				
					<div class="item-overlay"></div>
				
					<div class="back-buttons<?php echo ( $post_format == 'gallery' ) ? ' magnificgallery' : '' ;?>">
				
					<?php
					//set $mfp for Magific Popup
					if( $post_format == "audio" ||  $post_format == "video" ) {
						$mfp = "magnificpopup mfp-ajax";
					}elseif( $post_format == "quote" ){
						$mfp = "magnificpopup mfp-inline";
					}elseif( $post_format == "gallery" ){
						$mfp = "mfp-gallery";
					}elseif( $post_format == "image" || $post_format == "" ){
						$mfp = "magnificpopup mfp-image";
					}
					echo $zoom_button ? '<a href="'.$img_url.'" class="button tiny '.$mfp.'"  title="'.esc_attr(strip_tags(get_the_title())).'">'. as_post_format_icon_action().'</a>' : null;
					
					echo $img_urls_gallery ? $img_urls_gallery : null; // if post format is gallery
					
					echo $link_button ? '<a href="'. $link.'" class="button tiny" title="'. esc_attr(strip_tags(get_the_title())).'"><div class="icon icon-link" aria-hidden="true"></div></a>' : null;
					?>
					
					</div>
					
				</div>
				
				<?php echo $post_format == 'quote' ? '<div class="hidden-quote" id="quote-'.$post_id.'">'. $quote_html .'</div>' : null;
				?>
			
			</div><!-- .item-img -->
				
			<?php echo (!$zoom_button && !$link_button) ? '</a>' : ''; ?>
				
			<div class="item-data">
				
				<?php echo $post_title; ?>
				
				<div class="meta">
					<?php as_entry_date(); as_entry_author(); ?>
				</div>
				
				<div class="excerpt">
					
					<?php //echo '<p>' . apply_filters('as_custom_excerpt',80, true) . '</p>'; ?>
					<?php the_excerpt(); ?>
					
				</div>
			
				<div class="clearfix"></div>
				
				
				
			
			</div><!-- .item-data -->
			
	
		</div><!-- .column -->
		
		<?php 
		
		$i++;
		
	}// END foreach
	?>
	<script>
	(function( $ ){
		var startMagnificPopupAjax = window.magnificPopupSequoia();
		
	})( jQuery );
	</script>
	
	<?php 
	/* reset, clean buffer and respond with content */
	wp_reset_postdata();
	$response = ob_get_contents();
	ob_end_clean();

	echo $response;
	die(1);

}
/**
 *	QUICK VIEW - Products popup
 *
 */
add_action( 'wp_ajax_nopriv_load-filter3', 'quick_view' );// for NOT logged in users
add_action( 'wp_ajax_load-filter3', 'quick_view' );// for logged in users

function quick_view () {

	global $post, $product, $woocommerce_loop, $wp_query, $woocommerce;
	
	$productID	= $_POST[ 'productID' ];
	$lang 		= isset($_POST[ 'lang' ]) ? $_POST[ 'lang' ] : '';
		
	$prodID = $lang ? icl_object_id( $productID, 'product', false, $lang ) : $productID;
	
	$display_args = array(
			'no_found_rows'		=> 1,
			'post_status'		=> 'publish',
			'post_type'			=> 'product',
			//'post_parent'		=> 0,
			'suppress_filters'	=> false,
			'numberposts'		=> 1,
			'include'			=> $prodID
		);
			
	$content = get_posts($display_args);
	
	ob_start ();
	
	foreach ( $content as $post ) {
			
		setup_postdata( $post );
	
		global $post, $product, $woocommerce, $wp_query;
		
		$postClassarr = get_post_class();
		$postClass = implode(" ", $postClassarr );
		
		echo '<div itemscope itemtype="http://schema.org/Product" id="product-'. $productID .'" class="'. $postClass .' product">';
			
		/**
		 * woocommerce_show_product_images hook
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		// do_action( 'woocommerce_before_single_product_summary' ); // discarded
		
		do_action( 'sequoia_quick_view_images' );
	

		echo '<div class="summary entry-summary">';

		echo '<h4><a href="' .get_permalink(). '" title="'.get_the_title().'">' . get_the_title() .'</a></h4>';
		
		/**
		 * woocommerce_single_product_summary hook
		 *
		 * @hooked woocommerce_template_single_title - 5 // discarded
		 * @hooked woocommerce_template_single_price - 10
		 * @hooked woocommerce_template_single_excerpt - 20
		 * @hooked woocommerce_template_single_add_to_cart - 30
		 * @hooked woocommerce_template_single_meta - 40
		 * @hooked woocommerce_template_single_sharing - 50
		 */
		 
		// DON'T DO SHARETHIS ON QUICK VIEW
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
		
		do_action( 'woocommerce_single_product_summary' );

		
		
		echo '</div>'; // end .summary

		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_output_related_products - 20
		 */
		//do_action( 'woocommerce_after_single_product_summary' );


	echo '<div class="clearfix"></div></div>';

	}
	?>
	<script>
	jQuery(document).ready(function ($) {
		/* Get those variations forms to work ;) : */
		$(function() {
			// wc_add_to_cart_variation_params is required to continue, ensure the object exists
			if ( typeof wc_add_to_cart_variation_params === 'undefined' )
				return false;
			$('.variations_form').wc_variation_form();
			$('.variations_form .variations select').change();
		});
		
		/*	Quantity buttons:	*/
		//$("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").addClass('buttons_added').append('<input type="button" value="+" class="plus" />').prepend('<input type="button" value="-" class="minus" />');
		
		/*	OWL Carousel:	*/
		var images = $('#qv-holder').find('.images')
		if( images.hasClass("productslides") ) {
			images.owlCarousel({
						singleItem			: true,
						autoPlay			: false,
						navigation			: true,
						pagination			: false,
						autoHeight 			: true,
						mouseDrag			: true,
						rewindNav			: true,
						paginationNumbers	: false,
						navigationText		:["&#xe16d;","&#xe170;"]
					});
			cs_navig = cs_pagin = cs_auto = "";
		}
		
		/*	QUICK VIEW WINDOW VERTICAL CENTER POSITION :	*/

		$(window).resize(function() {
		
			var qv_holder		= $('#qv-holder'),
				qv_height	= qv_holder.outerHeight(true),
				qv_overlay	= $('.qv-overlay'),
				qv_overlay_h= qv_overlay.outerHeight(true);
		
			var	qv_top		= (qv_overlay_h / 2) - (qv_height/2);
			
			if ( qv_top <=  20 ) { // if modal goes off the top
				qv_top = 40;
			}
			
			qv_holder.stop(true,false).delay(200).animate({'top': qv_top },{ duration:400, easing: 'easeOutQuart'} );
			
		});//.trigger('resize')
		 
		$('.item-img:first-child img').on('load',function() {
			
			$(window).trigger('resize');
			
		});
		
		if ( $.isFunction( window.variableProductImages ) ) {
			var variableProductImages = window.variableProductImages();
		}
	});
	
	</script>
	<?php 
	
	
	/* reset, clean buffer and respond with content */
	wp_reset_postdata();
	$response = ob_get_contents();
	ob_end_clean();

	echo $response;
	die(1);
}

/**
 *	VARIATION IMAGES change for MAGNIFIER
 *
 */
add_action( 'wp_ajax_nopriv_var-image', 'variation_image' );// for NOT logged in users
add_action( 'wp_ajax_var-image', 'variation_image' );// for logged in users

function variation_image () {

	$varID	= $_POST[ 'var_id' ];

	$var_img_id 	= get_post_thumbnail_id( $varID );
	$var_img_src	= wp_get_attachment_image_src( $var_img_id, "full" );
	$var_img_url	= $var_img_src[0];

	echo esc_url($var_img_url);
	die(1);
}
/**
 *	INFINITE POSTS LOAD:
 *
 */
function add_posts() {
	$offset				= $_POST['offset'];
	$number_of_posts	= get_option( "posts_per_page" );
	$args				= array( "posts_per_page"=>$number_of_posts, "offset"=>$offset+1 );
	$posts				= new WP_Query( $args );
	?>

	<?php while($posts->have_posts()) {?>
		
		<?php $posts->the_post(); ?>
		
		<article class="infinite-post">
					
			<h2 class="post-title"><a href="<?php esc_attr(the_permalink()); ?>" title="<?php the_title_attribute(); ?>"><?php the_title();?></a></h2>
					
			<div class="simple-meta"><?php as_entry_author(); as_entry_date();?></div>
			
			<div class="post-content"><?php the_excerpt();?></div>
						
		</article>
		<?php
	}
	die();
}

add_action( "wp_ajax_nopriv_add_posts","add_posts" );
add_action( "wp_ajax_add_posts","add_posts" );

/**
 * end AJAX LOAD STUFF
 */