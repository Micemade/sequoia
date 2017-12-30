<?php
/**
 *	AS_Ajax_Categories.
 *
 *	block and class for displaying posts, portfolios or products.
 *	ajax load of items from selected category
 */
class AS_Ajax_Categories extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Ajax content',
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('as_ajax_categories', $block_options);
	}
	
	function animations_array() {
			
		include ( get_template_directory() .'/inc/functions/animations-icons-arrays.php' );
		return $block_enter_anim_arr;

	}
	
	function form($instance) {
		
		$sequoia_wc_active = apply_filters( "sequoia_wc_active","");
		
		$defaults = array(
			'title'				=> '',
			'subtitle'			=> '',
			'sub_position'		=> 'bellow',
			'title_style'		=> 'center',
			'enter_anim'		=> 'fadeIn',
			'post_type'			=> 'post',
			'post_cats'			=> '',
			'portfolio_cats'	=> '',
			'product_cats'		=> '',
			'block_style'		=> 'style1',
			'tax_menu_style'	=> 'inline',
			'img_format'		=> 'as-portrait',
			'custom_img_width'	=> '',
			'custom_img_height'	=> '',
			'total_items'		=> 8,
			'items_desktop'		=> 4,
			'items_desktop_small'=> 3,
			'items_tablet'		=> 2,
			'items_mobile'		=> 1,
			'only_featured' 	=> false,
			'use_slider'		=> true,
			'zoom_button'		=> true,
			'link_button'		=> true,
			'slider_navig'		=> true,
			'slider_pagin'		=> true,
			'slider_timing'		=> '',
			'anim'				=> 'anim-1',
			'data_anim'			=> 'none',
			'more_link_text'	=> 'Read more',
			'more_link_url'		=> '',
			'target'			=> '',
			'css_classes'		=> '',
		);
		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		
		?>
		<div class="description half">
		
			<label for="<?php echo $this->get_field_id('title') ?>">Block title (optional)</label>
				
			<?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
			
		</div>
		
		<div class="description half last">
		
			<label for="<?php echo $this->get_field_id('subtitle') ?>">Block subtitle (optional)</label>
				
			<?php echo aq_field_input('subtitle', $block_id, $subtitle, $size = 'full') ?>
			
		</div>
		
		<div class="description third">
			<label for="<?php echo $this->get_field_id('title_style') ?>">
				Block title style
			</label>	<br/>
			<?php
			$img_formats = array(
				'center'		=> 'Center',
				'float_left'	=> 'Float left',
				'float_right'	=> 'Float right'
				);
			echo aq_field_select('title_style', $block_id, $img_formats, $title_style); 
			?>	
		</div>
		
		<div class="description third">
		
			<label for="<?php echo $this->get_field_id('sub_position') ?>">
			Position of the subtitle</label><br/>
			
			<?php 
			$sub_options = array(
				'above'		=> 'Above heading',
				'bellow'	=> 'Bellow heading',
			);
			echo aq_field_select('sub_position', $block_id, $sub_options, $sub_position, $block_id); ?>
		
		</div>
		
		
		<div class="description third last">
		
			<label for="<?php echo $this->get_field_id('enter_anim') ?>">Viewport enter animation</label><br/>	
			
			<?php echo aq_field_select('enter_anim', $block_id, $this->animations_array(), $enter_anim ); ?>
			
		</div>
		
		<hr>	

		<?php // GET POST TYPES AND TAXONOMIES FOR FILTERING 
		$is_port_tax	= taxonomy_exists( 'portfolio_category' );
		?>
		
		<div class="description <?php echo $sequoia_wc_active ? 'fourth' : 'third'; ?>">
			<label for="<?php echo $this->get_field_id('post_type') ?>">
				Post type
			</label>	<br/>
			<?php
			
			if( $sequoia_wc_active ) {
				$post_types = array(
					'post'=> 'Post',
					'product' => 'Product',
					'portfolio' => 'Portfolio'
				);
			}else{
				$post_types = array(
					'post'=> 'Post',
					'portfolio' => 'Portfolio'
				);
			}
			echo aq_field_select('post_type', $block_id, $post_types, $post_type); 
			?>
		</div>
		
		<div class="description <?php echo $sequoia_wc_active ? 'fourth' : 'third'; ?>">
			
			<label for="<?php echo $this->get_field_id('post_cats') ?>">Post categories:</label><br/>
				
			<?php

			$post_cats_arr = apply_filters('as_terms', 'category' );
			
			echo aq_field_multiselect('post_cats', $block_id, $post_cats_arr, $post_cats); 
			?>
		</div>
		
					
		<div class="description <?php echo $sequoia_wc_active ? 'fourth' : 'third last'; ?>">
		
			<?php if( $is_port_tax ) { ?>
			
				<label for="<?php echo $this->get_field_id('portfolio_cats') ?>">Portfolio categories:</label><br/>
					
				<?php
				
				$terms_arr = apply_filters('as_terms', 'portfolio_category' );
				
				echo aq_field_multiselect('portfolio_cats', $block_id, $terms_arr, $portfolio_cats); 
				?>
				
			<?php 
			}else{
				
				echo '<p class="description">There is no <strong>"Portfolio category"</strong> taxonomy registered.<br /> <br /> Please, install and activate "Aligator Custom Post Types plugin</p>';
			}
			?>
		</div>	
								
		
		<?php if( $sequoia_wc_active ) { ?>
		<div class="description fourth last">
		
			<label for="<?php echo $this->get_field_id('product_cats') ?>">Product categories:</label><br/>
				
			<?php
			
			$product_cats_arr = apply_filters('as_terms', 'product_cat' );
			
			echo aq_field_multiselect('product_cats', $block_id, $product_cats_arr, $product_cats); 
			?>
			
		</div>
		<?php } ?>
		
		<div class="clearfix clear"></div>
		<hr />	
		
		
		<div class="description half">
			
			<label for="<?php echo $this->get_field_id('block_style') ?>">Block style</label><br/>	
			
			<?php
			$block_styles = array(
				'style1' => 'Style 1 (general)',
				'style2' => 'Style 2 (blog posts)',
				'style3' => 'Style 3 (portfolio)'
				);
			echo aq_field_select('block_style', $block_id, $block_styles, $block_style); 
			?>
			
			<p class="description">Block styles are pre-designed to fit for specific purpose: general (blog or portfolio), blog or portfolio</p>
			
		</div>		
		
		<div class="description half last">
			
			<label for="<?php echo $this->get_field_id('tax_menu_style') ?>">Taxonomy menu style</label><br/>	
			
			<?php
			$tax_menu_styles = array(
				'none'		=> 'None',
				'inline'	=> 'Inline menu',
				'dropdown'	=> 'Dropdown menu'
				);
			echo aq_field_select('tax_menu_style', $block_id, $tax_menu_styles, $tax_menu_style); 
			?>
			
		</div>		
		
		
		<div class="clearfix clear"></div>
		<hr />	
		
		
		<div class="description fourth ">	
			
			<label for="<?php echo $this->get_field_id('img_format') ?>">Image format</label><br/>	
			<?php
			$post_types = array(
				'thumbnail'		=> 'Thumbnail',
				'medium'		=> 'Medium',
				'as-portrait'	=> 'Sequoia portrait',
				'as-landscape'	=> 'Sequoia landscape',
				'large'			=> 'Large'
				);
			echo aq_field_select('img_format', $block_id, $post_types, $img_format); 
			?>
			
			<div class="clearfix clear"></div>
			
			<label for="<?php echo $this->get_field_id('custom_img_width') ?>">Custom image width</label><br />
			<?php echo aq_field_input('custom_img_width', $block_id, $custom_img_width, $size="min"); ?> px

			<div class="clearfix clear"></div>
			
			<label for="<?php echo $this->get_field_id('custom_img_height') ?>">Custom image height</label><br />
			<?php echo aq_field_input('custom_img_height', $block_id, $custom_img_height, $size="min"); ?> px
			
			<p class="description">"Custom image size" overrides "Image Format" (registered image sizes) settings. Use both width and height value.</p>
		
		</div>
		
		<div class="description fourth">
			
			<label for="<?php echo $this->get_field_id('zoom_button') ?>">Show zoom button ?</label><br />
			<?php echo aq_field_checkbox('zoom_button', $block_id, $zoom_button); ?>
			
			<div class="clearfix clear"></div>
			
			<label for="<?php echo $this->get_field_id('link_button') ?>">Show link button ?</label><br />
			<?php echo aq_field_checkbox('link_button', $block_id, $link_button); ?>
			
			<p class="description">If both zoom and link button are disabled the link to single product will apply to image</p>
			
			<hr>
			
			<label for="<?php echo $this->get_field_id('use_slider') ?>">Use slider ?</label><br />
			
			<?php echo aq_field_checkbox('use_slider', $block_id, $use_slider); ?>
			
			<div class="clearfix clear"></div>
			
			<label for="<?php echo $this->get_field_id('slider_pagin') ?>">Slider pagination</label><br />
			
			<?php echo aq_field_checkbox('slider_pagin', $block_id, $slider_pagin); ?>
			
			<div class="clearfix clear"></div>
					
			<label for="<?php echo $this->get_field_id('slider_navig') ?>">Slider navigation</label><br />
			
			<?php echo aq_field_checkbox('slider_navig', $block_id, $slider_navig); ?>
		
			<div class="clearfix clear"></div>
					
			<label for="<?php echo $this->get_field_id('slider_timing') ?>">Slider timing</label><br />
			<?php echo aq_field_input('slider_timing', $block_id, $slider_timing, $size = 'min');	?>
			
			<p class="description">If empty, the slider will not slide automatically </p>
		
			<hr>

		
		</div>	
		
		<div class="description fourth">
			
			<label for="<?php echo $this->get_field_id('total_items') ?>">Total items</label><br />
			<?php echo aq_field_input('total_items', $block_id, $total_items, $size="min"); ?>
			
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('items_desktop') ?>">Items in desktop width</label><br />
			<?php
			$items_desk_array = array(
				'1'		=> '1',
				'2'		=> '2',
				'3'		=> '3',
				'4'		=> '4',
				'6'		=> '6'
				);
			echo aq_field_select('items_desktop', $block_id, $items_desk_array, $items_desktop); 
			?>
			
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('items_desktop_small') ?>">Items in desktop smaller</label><br />
			<?php
			$items_desk_small_array = array(
				'1'		=> '1',
				'2'		=> '2',
				'3'		=> '3',
				'4'		=> '4',
				'6'		=> '6'
				);
			echo aq_field_select('items_desktop_small', $block_id, $items_desk_small_array, $items_desktop_small); 
			?>
			
			<div class="clearfix"></div>
						
			<label for="<?php echo $this->get_field_id('items_tablet') ?>">Items in tablet view</label><br />
			<?php
			$items_tablet_array = array(
				'1'		=> '1',
				'2'		=> '2',
				'3'		=> '3',
				'4'		=> '4',
				'6'		=> '6'
				);
			echo aq_field_select('items_tablet', $block_id, $items_tablet_array, $items_tablet); 
			?>
			
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('items_mobile') ?>">Items in mobile view</label><br />
			<?php
			$items_mobile_array = array(
				'1'		=> '1',
				'2'		=> '2',
				'3'		=> '3',
				'4'		=> '4',
				'6'		=> '6'
				);
			echo aq_field_select('items_mobile', $block_id, $items_mobile_array, $items_mobile); 
			?>
		
		</div>	
		
		<div class="description fourth last">
			
			<label for="<?php echo $this->get_field_id('only_featured') ?>">Display only featured ?</label><br />
			<?php echo aq_field_checkbox('only_featured', $block_id, $only_featured); ?>

			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('anim') ?>">Hover image animation</label><br />
			<?php
			$anim_array = array(
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
				);
			echo aq_field_select('anim', $block_id, $anim_array, $anim); 
			?>
			
			<label for="<?php echo $this->get_field_id('data_anim') ?>">Post text animation</label><br />
			<?php
			$anim_array = array(
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

				);
			echo aq_field_select('data_anim', $block_id, $anim_array, $data_anim); 
			?>
			
		</div>
		

		<hr>		
		<div class="clearfix clear"></div>
		
		<div class="description third">	
			<label for="<?php echo $this->get_field_id('more_link_text') ?>">Text for "more" link</label>
			<?php echo aq_field_input('more_link_text', $block_id, $more_link_text, $size = 'full') ?>
		</div>	
				
		<div class="description third">	
			<label for="<?php echo $this->get_field_id('more_link_url') ?>">URL address  for "more" link</label>
			<?php echo aq_field_input('more_link_url', $block_id, $more_link_url, $size = 'full') ?>
		</div>	
				
		<div class="description third last">
			
			<label for="<?php echo $this->get_field_id('target') ?>">Open in new tab/window</label><br />
			
			<?php echo aq_field_checkbox('target', $block_id, $target); ?>
		
		</div>
		
		<div class="clearfix clear"></div>
		
		<div class="description">	
			If either of these two fields are empty, the button "more" button won't display.
		</div>
		
		<hr>
		
		<div class="description">	
			<label for="<?php echo $this->get_field_id('css_classes') ?>">Additional CSS classes</label>
			<?php echo aq_field_input('css_classes', $block_id, $css_classes, $size = 'full') ?>
		
			<p class="description small">Add custom css classes, defined in child theme css file, or custom css (in Theme options). Multiple classes can be added, separated by space.</p>
		</div>	
				
		
		
	<?php
	
	}
	
	function block($instance) {
		
		global $post, $product, $woocommerce_loop, $wp_query, $woocommerce;
		
		extract($instance);
		
		if ( $enter_anim != 'none' && !wp_style_is( 'animate', 'enqueued' )) {
				
			wp_register_style( 'animate', get_template_directory_uri() . '/css/animate.css');
			wp_enqueue_style( 'animate' );
			
		}
		if ( !wp_style_is( 'wp-mediaelement', 'enqueued' )) {
				
			wp_enqueue_script('wp-mediaelement');
			wp_enqueue_style( 'wp-mediaelement' );
			
		}
		
		
		
		$sticky_array = get_option( 'sticky_posts' );
		$total_items = $total_items ? $total_items : -1;
		
		// FEATURED POSTS FILTER ARGS
		if ( $post_type == 'post' && $only_featured ) {
			$args_only_featured = array('post__in' => $sticky_array);
		}elseif ( $post_type == 'portfolio' && $only_featured ){
			$args_only_featured = array( 
				'meta_key' => 'as_featured_item',
				'meta_value' => 1
			);
		}elseif ( $post_type == 'product' && $only_featured ){
			
			if( apply_filters( 'sequoia_wc_version', '3.0.0' ) ) {
				$args_only_featured['tax_query'] = array(
					array(
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
					)
				);
			}else{
				$args_only_featured = array( 
					'meta_key' => '_featured',
					'meta_value' => 'yes'
				);
			}
			
		}else{
			$args_only_featured = array();
		}
		//
		
		// TAXONOMY FILTER ARGS
		if( isset( $post_cats) &&  $post_type == 'post' ) {
			$tax_terms = $post_cats;
			$taxonomy = 'category';
		}elseif( isset($portfolio_cats) && $post_type == 'portfolio' ){
			$tax_terms = $portfolio_cats;
			$taxonomy = 'portfolio_category';
		}elseif( isset($product_cats) && $post_type == 'product' ){
			$tax_terms = $product_cats;
			$taxonomy = 'product_cat';
		}else{
			$tax_terms = '';
			$taxonomy = '';
		}
		
		
		####################  HTML STARTS HERE: ###########################
		echo $css_classes ? '<div class="'.esc_attr($css_classes).'">' : null;
		?>
		
		
		<div class="header-holder <?php echo $title_style; ?>">
		<?php 
		echo  ( $subtitle && $sub_position == 'above' ) ? '<div class="block-subtitle above '. $title_style .'">' . $subtitle . '</div>' : ''; 
		
		echo $title ? '<h2 class="categories-block block-title '. $title_style .'">' . $title . '</h2>' : '';
		
		echo  ( $subtitle && $sub_position == 'bellow' ) ? '<div class="block-subtitle bellow '. $title_style .'">' . $subtitle . '</div>' : ''; 
		?>
		</div>
		
		<?php 
		if( $tax_terms && $tax_menu_style != 'none') {
		
			echo $tax_menu_style == 'dropdown' ?'<div class="menu-toggler block-tax-toggler"><a href="#" title="'.__('Toggle categories','sequoia').'" class="button tiny"><span class="icon-menu-2"></span></a></div>' : null;
			?>
			
			<ul class="taxonomy-menu <?php echo $tax_menu_style == 'dropdown' ? ' tax-dropdown' : null; ?>">
			
			<?php 
			// GET TAXONOMY OBJECT:
			foreach ( $tax_terms as $term ) {
				$to = get_term_by( 'slug', $term, $taxonomy );
				
				if( !$to  ) {
					continue;
				}
			
				echo '<li class="category-link" id="cat-'. esc_attr( $to->term_id ).'">';
				echo '<a href="#" class="'.esc_attr( $to->slug ).' ajax-posts" data-id="'. esc_attr( $to->slug) .'">';
				echo '<div class="term">' . esc_html( $to->name ). '</div>';
				echo '</a>';
				echo '</li>';
				
			}
			?>
			</ul>
		
		<?php } // endif $tax_terms ?>
		
		<?php
		// IF CUSTOM IMAGE SIZE ( both width and height required )
		if( $custom_img_width && $custom_img_height ) {
			$img_width = $custom_img_width ? $custom_img_width : 450;
			$img_height = $custom_img_height ? $custom_img_height : 300;
		}else{
			$img_width	= '';
			$img_height = '';
		}				
		
		// IN CASE NO SLIDER IS USED - ECHO THE GRID
		$l = 12 / $items_desktop;
		$t = 12 / $items_tablet;
		$m = 12 / $items_mobile;
		
		if ( !$use_slider ) {
			$no_slider_grid = 'large-'.$l.' medium-'. $t . ' small-'.$m;
		}else{
			$no_slider_grid = '';
		}
		?>
		
		<div class="clearfix"></div>
		
		<?php 
		// if there are taxonomies selected, turn on taxonomy filter:
		if( !empty($tax_terms) ) {

			$tax_filter_args = array('tax_query' => array(
								array(
									'taxonomy' => $taxonomy,
									'field' => 'slug', // can be 'slug' too
									'operator' => 'IN', // NOT IN to exclude
									'terms' => $tax_terms
								)
							)
						);
		}else{
			$tax_filter_args = array();
		}
		$main_args = array(
			'no_found_rows' => 1,
			'post_status'	=> 'publish',
			'post_type'		=> $post_type,
			'post_parent'	=> 0,
			'suppress_filters' => false,
			'orderby'     	=> 'post_date',
			'order'       	=> 'DESC',
			'numberposts' 	=> $total_items
		);
		
		$all_args = array_merge( $main_args, $args_only_featured, $tax_filter_args );

		$content = get_posts($all_args);

		
		/*
		IMPORTANT: HIDDEN INPUT TYPE - HOLDER OF VARS SENT VIA POST BY AJAX :
		*/ 
		?>
		<input type="hidden" class="varsHolder" data-tax = "<?php echo $taxonomy; ?>" data-block_id="<?php echo $block_id; ?>"  data-ptype = "<?php echo $post_type; ?>" data-totitems = "<?php echo $total_items; ?>" data-feat = "<?php echo $only_featured; ?>"  data-useslider ="<?php echo $use_slider; ?>" data-img = <?php echo $img_format; ?>  data-custom-img-w="<?php echo $custom_img_width; ?>" data-custom-img-h="<?php echo $custom_img_height; ?>"  data-taxmenustlye="<?php echo $tax_menu_style; ?>"  data-enter_anim="<?php echo $enter_anim; ?>" data-no_slider_grid="<?php echo $no_slider_grid; ?>" data-zoom="<?php echo $zoom_button ?>" data-link="<?php echo $link_button ?>" />
		
		
		<div class="loading-animation" style="display: none;"><?php echo __('Loading ...','sequoia'); ?></div>
		
		
		<script>
		(function( $ ){
			$.fn.anim_waypoints_posts = function(blockId, enter_anim) {
				
				var thisBlock = $('#posts-'+ blockId );
				if ( !window.isMobile && !window.isIE9 ) {
					var item = thisBlock.find('.item');
					
					item.waypoint(
						function(direction) {
							var item_ = $(this);
							if( direction === "up" ) {	
								item_.removeClass('animated '+ enter_anim).addClass('to-anim');
							}else if( direction === "down" ) {
								var i =  $(this).attr('data-i');
								setTimeout(function(){
								   item_.addClass('animated '+ enter_anim).removeClass('to-anim');
								}, 50 * i);
							}
						}
						
					,{ offset: "98%" });
				}else{
					thisBlock.find('.item').each( function() {
						$(this).removeClass('to-anim');
					});
				}
								
			}
		})( jQuery );
		
		jQuery(document).ready( function($) {
			
			$(document).anim_waypoints_posts("<?php echo $block_id; ?>"," <?php echo $enter_anim;?>");
		
		});
		</script>
		
		
		<div id="posts-<?php echo $block_id; ?>" class="content-block cb-2">
			
			<?php if( !empty( $tax_terms )) { ?>
			<div class="cat-title">
				<div class="wrap"></div>
				<a href="#" class="ajax-posts"<?php echo !empty( $tax_terms ) ? ' data-id="' . implode(",", $tax_terms) .'"' : null; // array to string ?>>
					<?php $t = __('Reset categories','sequoia'); ?>
					<div class="icon-close" aria-hidden="true" title="<?php echo $t; ?>"></div>
				</a>
			</div>
			<?php } ?>			
		
			
			<input type="hidden" class="slides-config" data-navigation="<?php echo $slider_navig; ?>" data-pagination="<?php echo $slider_pagin; ?>" data-auto="<?php echo $slider_timing; ?>" data-desktop="<?php echo $items_desktop; ?>" data-desktop-small="<?php echo $items_desktop_small; ?>" data-tablet="<?php echo $items_tablet; ?>" data-mobile="<?php echo $items_mobile; ?>" />
			
			<div class="<?php echo $use_slider ? ' contentslides' : '';?> category-content <?php echo ' '.$anim ;?> <?php echo $data_anim == 'none' ? '' : $data_anim; ?><?php echo ' '.$block_style; ?>">
			
			<?php 
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
			
			wp_reset_query();
			
			?>
			</div>

		
			<?php if( $more_link_text && $more_link_url ) { ?>
			<div class="bottom-block-link">
			
				<a href="<?php echo $more_link_url ; ?>" title="<?php echo esc_attr($more_link_text); ?>" class="button" <?php echo $target ? 'target="_blank"' : null; ?>>
					<?php echo esc_html($more_link_text); ?>
				</a>
				
			</div>
			<?php } //endif; $more_link_text ?>
			
			<div class="clearfix"></div>
			
		</div><!-- /.content-block -->
		
	<?php
	####################  HTML ENDS HERE: ###########################
	echo $css_classes ? '</div>' : null;
	?>
		
	<?php
	
	}/// END func block
	
	function update($new_instance, $old_instance) {
		return $new_instance;
	}
} ?>