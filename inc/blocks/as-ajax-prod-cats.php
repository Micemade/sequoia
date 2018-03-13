<?php
/**
 *	AS_Ajax_Product_Categories.
 *
 *	block and class for displaying products.
 *	ajax load of items from selected product category
 */
class AS_Ajax_Product_Categories extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Ajax products ',
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('as_ajax_product_categories', $block_options);
	}
	
	function animations_array() {
			
		include ( get_template_directory() .'/inc/functions/animations-icons-arrays.php' );
		return $block_enter_anim_arr;

	}
	
	function form($instance) {
		
		$sequoia_wc_active = apply_filters( 'sequoia_wc_active',"");
		
		$defaults = array(
			'title'				=> '',
			'subtitle'			=> '',
			'sub_position'		=> 'bellow',
			'title_style'		=> 'center',
			'enter_anim'		=> 'fadeIn',
			'post_type'			=> 'product',
			'img_format'		=> 'as-portrait',
			'shop_quick'		=> true,
			'shop_buy_action'	=> true,
			'shop_wishlist'		=> true,
			'anim'				=> 'anim-1',
			'data_anim'			=> 'none',
			'total_items'		=> 6,
			'filters'			=> 'latest',
			'use_slider'		=> true,
			'zoom_button'		=> true,
			'link_button'		=> true,
			'slider_navig'		=> true,
			'slider_pagin'		=> true,
			'slider_timing'		=> '',
			'items_desktop'		=>  4,
			'items_desktop_small' => 3,
			'items_tablet'		=>	2,
			'items_mobile'		=> 1,
			'prod_cat_menu'		=> 'images',
			'menu_columns'		=> 'auto',
			'text_color'		=> '',
			'overlay_color'		=> '',
			'product_cats'		=> '',
			'button_text'		=> '',
			'button_link'		=> '',
			'target'			=> '',
			'css_classes'		=> ''
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
		
		<div class="description third ">
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
		
		<div class="description fourth">
			
			<label for="<?php echo $this->get_field_id('product_cats') ?>">Product categories:</label><br/>
			<?php
			if( $sequoia_wc_active ) {

				$terms_arr = apply_filters('as_terms', 'product_cat' );
				
				echo aq_field_multiselect('product_cats', $block_id, $terms_arr, $product_cats); 
			
			}else{
				echo '<p class="description">WooCommerce plugin is not active. Please, activate it to use product categories.</p>';
			}
			?>
			
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('filters') ?>">Special filters</label><br />
			<?php
			$filters_array = array(
				'latest'		=> 'Latest products',
				'featured'		=> 'Featured products',
				'on_sale'		=> 'Products on sale',
				'best_sellers'	=> 'Best selling products',
				'best_rated'	=> 'Best rated products',
				'random'		=> 'Random products'
				);
			echo aq_field_select('filters', $block_id, $filters_array, $filters); 
			?>
			
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('prod_cat_menu') ?>">Categories menu</label><br />
			<?php
			$prod_cat_menu_arr = array(
				'none'			=> 'None',
				'images'		=> 'With category images',
				'no_images'		=> 'Without category images',
				);
			echo aq_field_select('prod_cat_menu', $block_id, $prod_cat_menu_arr, $prod_cat_menu); 
			?>
			
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('menu_columns') ?>">Menu columns</label><br />
			<?php
			$menu_columns_arr = array(
				'auto'		=> 'Auto float',
				'stretch'	=> 'Auto stretch',
				'1'			=> '1',
				'2'			=> '2',
				'3'			=> '3',
				'4'			=> '4',
				'6'			=> '6'
				);
			echo aq_field_select('menu_columns', $block_id, $menu_columns_arr, $menu_columns); 
			?>
			
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('text_color') ?>"><?php echo __('Text over category image color','sequoia'); ?>
			</label><br />
			<?php echo aq_field_color_picker('text_color', $this->block_id, $text_color, $text_color ) ?>	
			
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('overlay_color') ?>"><?php echo __('Category image overlay color','sequoia'); ?>
			</label><br />
			<?php echo aq_field_color_picker('overlay_color', $this->block_id, $overlay_color, $overlay_color) ?>	
			
			
		</div>

		
		<div class="description fourth">
			
			<label for="<?php echo $this->get_field_id('img_format') ?>">
				Product image format
			</label>	<br/>
			<?php
			$img_formats = array(
				'thumbnail'		=> 'Thumbnail',
				'medium'		=> 'Medium',
				'as-portrait'	=> 'Sequoia portrait',
				'as-landscape'	=> 'Sequoia landscape',
				'large'			=> 'Large'
				);
			echo aq_field_select('img_format', $block_id, $img_formats, $img_format); 
			?>
			
			<hr>
			
			<label for="<?php echo $this->get_field_id('shop_quick') ?>">Show quick view button: </label>
			<br />			
			<?php echo aq_field_checkbox('shop_quick', $block_id, $shop_quick); ?>
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('shop_buy_action') ?>">Show buy button: </label>
			<br />			
			<?php echo aq_field_checkbox('shop_buy_action', $block_id, $shop_buy_action); ?>
			<div class="clearfix"></div>
		
			<label for="<?php echo $this->get_field_id('shop_wishlist') ?>">Show wishlist button: </label>
			<br />			
			<?php echo aq_field_checkbox('shop_wishlist', $block_id, $shop_wishlist); ?>
		
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('anim') ?>">Hover animation</label><br />
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
			
			<label for="<?php echo $this->get_field_id('data_anim') ?>">Product info animation</label><br />
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
			

		</div>
		
							
		<div class="description fourth last">
		
			<label for="<?php echo $this->get_field_id('total_items') ?>">Total items to display</label><br />
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

			<div class="clearfix"></div>
			
		
		</div>
		
		<div class="clearfix clear"></div>
		<hr />
		
		<p class="description">If using slider, "In one slide" setting will apply. If not using slider, total items will be displayed, in number of columns per row set in "On one row" setting. If checked "Only featured products?", only featured products will be displayed.</p>
	
		<hr />
		
		<div class="description third">	
			<label for="<?php echo $this->get_field_id('button_text') ?>">Button text</label>
			<?php echo aq_field_input('button_text', $block_id, $button_text, $size = 'full') ?>
		</div>	
				
		<div class="description third">	
			<label for="<?php echo $this->get_field_id('button_link') ?>">Button link</label>
			<?php echo aq_field_input('button_link', $block_id, $button_link, $size = 'full') ?>
		</div>
		
		<div class="description third last">
			
			<label for="<?php echo $this->get_field_id('target') ?>">Open in new tab/window</label><br />
			<?php echo aq_field_checkbox('target', $block_id, $target); ?>
		
		</div>
		
		<p class="description clearfix">	
			Both upper fields must be filled to display the button.
		</p>
		
		<hr>
		
		<div class="description">	
			<label for="<?php echo $this->get_field_id('css_classes') ?>">Additional CSS classes</label>
			<?php echo aq_field_input('css_classes', $block_id, $css_classes, $size = 'full') ?>
		
			<p class="description small">Add custom css classes, defined in child theme css file, or custom css (in Theme options). Multiple classes can be added, separated by space.</p>
		</div>	
		
	<?php
	
	}
	
	function block($instance) {
		
		global $post, $sequoia_wishlist_is_active, $product, $woocommerce_loop, $wp_query, $woocommerce;
		
		$sequoia_wc_active = apply_filters( 'sequoia_wc_active',"");
		
		extract($instance);
		
		if ( $enter_anim != 'none' && !wp_style_is( 'animate', 'enqueued' )) {
				
			wp_register_style( 'animate', get_template_directory_uri() . '/css/animate.css');
			wp_enqueue_style( 'animate' );
			
		}
	
		if( $sequoia_wc_active  ) {
		
		$total_items = $total_items ? $total_items : -1;
		
		
		// SET POST TYPE VARIABLE
		$post_type = 'product';
		
		// PRODUCT FILTERS:
		$order_rand	= false;
		$args_filters = array();
		if ( $filters == 'featured' ){
			
			$args_filters['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
			);
			
		}elseif( $filters == 'on_sale' ) {
			
			$product_ids_on_sale    = wc_get_product_ids_on_sale();
			if( ! empty( $product_ids_on_sale ) ) {
				$args_filters['post__in'] = $product_ids_on_sale;
			}
		
		}elseif( $filters == 'best_sellers' ) {
			
			$args_filters['meta_key']	= 'total_sales';
			$args_filters['orderby']	= 'meta_value_num';
		
		}elseif( $filters == 'best_rated' ) {
			
			$args_filters['meta_key']	= '_wc_average_rating';
			$args_filters['orderby']	= 'meta_value_num';
			
		}elseif( $filters == 'random' ) {
		
			$order_rand	= true;
			$args_filters = array();
			$args_filters['orderby'] = 'rand menu_order date';
			
		}
		// end filters
		//
		// TAXONOMY FILTER ARGS
		if( isset($product_cats)  ){
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
		// DISPLAY BLOCK TITLE AND "SUBTITLE":
		echo ( $subtitle && $sub_position == 'above' ) ? '<div class="block-subtitle above '. $title_style .' ">' . $subtitle . '</div>' : ''; 
		
		echo $title ? '<h2 class="categories-block block-title '. $title_style .'">' . $title . '</h2>' : '';
		
		echo ( $subtitle && $sub_position == 'bellow' ) ? '<div class="block-subtitle bellow '. $title_style .' ">' . $subtitle . '</div>' : ''; 
		?>
		</div>
		
		<?php
		
		if( $tax_terms && $prod_cat_menu != 'none' ) {

		
		echo '<ul class="taxonomy-menu cat-images" id="prod-cats-'.$block_id.'">';
		
		if ( $text_color ) {
			echo '<style scoped>';
			echo $text_color ? 'ul#prod-cats-'.$block_id.'  .category-image .term h4 { color: '.$text_color.';}' : null;						
			echo '</style>';
		}
		if ( $overlay_color ) {
			echo '<style scoped>';
			echo $overlay_color ? 'ul#prod-cats-'.$block_id.' .category-image a .item-overlay { background-color: '.$overlay_color.';}' : null;
			echo '</style>';
		}			
		
		
		// GET TAXONOMY OBJECT:
		$term_Objects = array();
		foreach ( $tax_terms as $term ) {
			$term_Objects[] = get_term_by( 'slug', $term, $taxonomy ); // get term object using slug
			
		}
		// menu items columns	
		if( $menu_columns == 'auto') {
			$grid_cat = '';
		}elseif( $menu_columns == 'stretch' ){
			$grid_cat = ' large-' . floor( 12 / count($term_Objects) ) . ' medium-' . floor( 12 / count($term_Objects) ) . ' small-12 column';
		}else{
			$grid_cat = ' large-' . floor( 12 / $menu_columns ) . ' medium-' . floor( 12 / $menu_columns ) . ' small-12 column';
		}
		
		$num_terms = count($term_Objects);
				
		// DISPLAY TAXONOMY MENU:
		if( !$num_terms ) {
			
			foreach ( $term_Objects as $term_obj ) {
				
				if( $prod_cat_menu == 'images' ) { // if images should be displayed:
				
					$thumbnail_id = get_woocommerce_term_meta( $term_obj->term_id, 'thumbnail_id' );
					$image = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail' );

					if ( $image ) {
			
						echo '<li id="cat-'.$term_obj->term_id .'" class="category-image'. $grid_cat .' as-hover">';
						echo ($num_terms > 1) ? '<a href="#" class="'.$term_obj->slug .' ajax-products" data-id="'. $term_obj->slug .'">' : '<div>';
						
						echo '<div class="item-overlay"></div>';
						
						echo '<div class="term"><div class="table"><div class="tablerow"><div class="tablecell">';
						
						
						echo '<h4 class="box-title">' . $term_obj->name . '</h4></div></div></div></div>';
						echo '<img src="' . bfi_thumb( $image[0], array( 'width' => 600, 'height' => 250 ) ). '" alt="" />';
						echo '<div class="arrow-down"></div>';
						echo ($num_terms > 1) ? '</a>' : '</div>';
						echo '</li>';
						
					}else{
					
						echo '<li id="cat-'.$term_obj->term_id .'" class="category-image'. $grid_cat .' as-hover">';
						echo ($num_terms > 1) ? '<a href="#" class="'.$term_obj->slug .' ajax-products" data-id="'. $term_obj->slug .'">' : '<div>';
						
						echo '<div class="item-overlay">';
						if ( $overlay_color ) {
							echo '<style scoped>';
							echo $overlay_color ? '#prod-cats-'.$block_id.' ul .category-image a .item-overlay { background-color: '.$overlay_color.';}' : null;
							echo '</style>';
						}
						echo '</div>';
						
						echo '<div class="term"><div class="table"><div class="tablerow"><div class="tablecell">';
						
						if ( $text_color ) {
							echo '<style scoped>';
							echo $text_color ? '#prod-cats-'.$block_id.' ul .category-image .term h4 { color: '.$text_color.';}' : null;						
							echo '</style>';
						}
						
						echo '<h4 class="box-title">' . $term_obj->name . '</h4></div></div></div></div>';
						echo '<img src="' . bfi_thumb( PLACEHOLDER_IMAGE, array( 'width' => 600, 'height' => 250 ) ) . '" alt="" />';
						echo '<div class="arrow-down"></div>';
						echo ($num_terms > 1) ? '</a>' : '</div>';
						echo '</li>';
					}
					
				}elseif( $prod_cat_menu == 'no_images' ){
				
					echo '<li id="cat-'.$term_obj->term_id .'" class="category-link'. $grid_cat .'">';
					echo '<a href="#" class="'.$term_obj->slug .' ajax-products" data-id="'. $term_obj->slug .'">';
					echo '<div class="term box-title">' . $term_obj->name . '</div>';
					echo '</a>';
					echo '</li>';
				}
			
			}	// end foreach
		}// endif
		?>
		</ul>
		
		<?php }// endif $tax_terms ?>
		
		<?php 	
		// IN CASE NO SLIDER IS USED - ECHO THE GRID
		$l = 12 / $items_desktop;
		$t = 12 / $items_tablet;
		$m = 12 / $items_mobile;
		
		if ( !$use_slider ) {
			$no_slider_grid = ' large-'.$l.' medium-'. $t . ' small-'.$m;
		}else{
			$no_slider_grid = '';
		}
		/*
		IMPORTANT: HIDDEN INPUT TYPE - HOLDER OF VARS SENT VIA POST BY AJAX :
		*/
		?>
		<input type="hidden" class="varsHolder" name="ajax-vars" data-block_id="<?php echo $block_id; ?>" data-tax = "<?php echo $taxonomy; ?>"  data-ptype = "<?php echo $post_type; ?>" data-totitems = "<?php echo $total_items; ?>" data-filters = "<?php echo $filters; ?>"  data-img= "<?php echo $img_format; ?>"  data-shop_quick ="<?php echo $shop_quick; ?>" data-shop_buy_action ="<?php echo $shop_buy_action; ?>" data-shop_wishlist ="<?php echo $shop_wishlist; ?>" data-enter_anim="<?php echo $enter_anim; ?>" data-no_slider_grid="<?php echo $no_slider_grid; ?>" data-zoom="<?php echo $zoom_button ?>" data-link="<?php echo $link_button ?>" />
		
		
		<div class="clearfix"></div>

		<?php 
		
		// if there are taxonomies selected, turn on taxonomy filter:
		if( !empty($tax_terms) ) {
			
			$tax_filter_args = array('tax_query' => array(
								array(
									'taxonomy' => $taxonomy,
									'field' => 'slug', // can be 'slug' or 'id'
									'operator' => 'IN', // NOT IN to exclude
									'terms' => $tax_terms,
									'include_children' => true
								)
							)
						);
		}else{
			$tax_filter_args = array();
		}
			
		
		$main_args = array(
			'no_found_rows'		=> 0,
			'post_status'		=> 'publish',
			'post_type'			=> $post_type,
			'post_parent'		=> 0,
			'suppress_filters'	=> false,
			'orderby'			=> $order_rand ? 'rand menu_order date' : 'menu_order date',
			'order'				=> 'DESC',
			'numberposts'		=> $total_items
		);
		
		$all_args = array_merge( $main_args, $args_filters, $tax_filter_args );

		$content = get_posts($all_args);
		
		?>	
	
		<div class="loading-animation" style="display: none;"><?php echo __('Loading products','sequoia'); ?></div>
		
		<script>
		(function( $ ){
			$.fn.anim_waypoints = function(blockId, enter_anim) {
				
				var thisBlock = $('#products-'+ blockId );
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
			
			
			$(document).anim_waypoints("<?php echo $block_id; ?>"," <?php echo $enter_anim;?>");
		
		});
		</script>
		
		<div id="products-<?php echo $block_id; ?>" class="content-block cb-1 woocommerce">

			<?php if( !empty( $tax_terms )) {?>
			<div class="cat-title">
				<div class="wrap"></div>
				<a href="#" class="ajax-products"<?php echo !empty( $tax_terms ) ? ' data-id="' . implode(",", $tax_terms) .'"' : null; // array to string ?>>
					<?php $t = __('Reset categories','sequoia');?>
					<div class="icon-close" aria-hidden="true" title="<?php echo $t; ?>"></div> 
				</a>

			</div>
			<?php } ?>
			
			<input type="hidden" class="slides-config" data-navigation="<?php echo $slider_navig; ?>" data-pagination="<?php echo $slider_pagin; ?>" data-auto="<?php echo $slider_timing; ?>" data-desktop="<?php echo $items_desktop; ?>" data-desktop-small="<?php echo $items_desktop_small; ?>" data-tablet="<?php echo $items_tablet; ?>" data-mobile="<?php echo $items_mobile; ?>" />
			
			<div class="category-content<?php echo $use_slider ? ' contentslides' : '';?><?php echo ' '.$anim ;?> <?php echo $data_anim == 'none' ? '' : $data_anim; ?>">
			
			<?php 
			$i = 1;
			
			//start products loop
			foreach ( $content as $post ) {
				
				setup_postdata( $post );
				
				global $product, $yith_wcwl;
				/* 
				// Hiding product :
				if ( ! $product || ! $product->is_visible() || !$product->is_in_stock() ) {
					continue;
				}
				 */
				
				if( defined('WPML_ON') ) { // if WPML plugin is active
					$id	= icl_object_id( get_the_ID(), 'product', false, ICL_LANGUAGE_CODE ); 
					$lang_code	= ICL_LANGUAGE_CODE;
				}else{
					$id	= get_the_ID();
					$lang_code	= '';
				}
				$link = get_permalink($id);
				
				
				// DATA for back image
				// 3.0.0 < Fallback conditional
				if( apply_filters( 'sequoia_wc_version', '3.0.0' )	) {
					$attachment_ids   = $product->get_gallery_image_ids();
				}else{
					$attachment_ids   = $product->get_gallery_attachment_ids();
				}
				$img_width = $img_height = "";
				if ( $attachment_ids ) {
					$image_url = wp_get_attachment_image_src( $attachment_ids[0], 'full'  );
					$img_url = $image_url[0];
					/* // IMAGE SIZES:
					$imgSizes = all_image_sizes(); // as custom fuction
					$img_width = $imgSizes[$img_format]['width'];
					$img_height = $imgSizes[$img_format]['height']; */
				}
				// end DATA
				
				
				$prod_title = '<h4 class="prod-title"><a href="'. $link .'" title="'.esc_attr(get_the_title()).'"> ' . esc_attr(get_the_title()) .'</a></h4>';
				?>

				<div class="column item<?php echo $no_slider_grid; ?><?php echo ($enter_anim != 'none') ? ' to-anim' : '';  ?>" data-i="<?php echo $i; ?>">
					
					<?php echo (!$zoom_button && !$link_button) ? '<a href="'.$link.'" title="'. esc_attr(get_the_title()) .'">' : ''; ?>
					
					<div class="item-img">
						
						<div class="front">
						
							<?php function_exists('woocommerce_show_product_loop_sale_flash') ? woocommerce_show_product_loop_sale_flash() : ''; ?>							
							<?php echo as_image( $img_format ); ?>
						
						</div>
						
						<div class="back">
						
							<div class="item-overlay"></div>

							<?php 
							
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
							
							function_exists('woocommerce_template_loop_rating') ? woocommerce_template_loop_rating() : '';
							
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
			}// END foreach
			
			wp_reset_query();
			
			?>
						
			</div>
			
			
			<?php if( $button_text && $button_link ) { ?>
			<div class="bottom-block-link">
			
				<a href="<?php echo $button_link ; ?>" title="<?php echo esc_attr($button_text); ?>" class="button" <?php echo $target ? 'target="_blank"' : null; ?>>
					<?php echo esc_html($button_text); ?>
				</a>
				
			</div>
			<?php } //endif; $button_text ?>
			
			<div class="clearfix"></div>
			
		</div><!-- /.content-block cb-1 -->
		
		<?php
		####################  HTML ENDS HERE: ###########################
		echo $css_classes ? '</div>' : null;
		?>
		
		<?php
	
		}else{
			
			echo '<h5 class="no-woo-notice">' . __('AJAX PRODUCTS BLOCK DISABLED.<br> Sorry, it seems like WooCommerce is not active. Please install and activate last version of WooCommerce.','sequoia') . '</h5>';
				return;
				
		} // end if $sequoia_wc_active 
	
	}/// END func block
	
	function update($new_instance, $old_instance) {
		return $new_instance;
	}
} ?>