<?php
class AS_Slider_Block extends AQ_Block {

	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Slider block',
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('as_slider_block', $block_options);
	}
	
	function form($instance) {
		
		
		$defaults = array(
			'style'				=> 'style1',
			'anim'				=> 'anim1',
			'post_type'			=> 'portfolio',
			'post_cats'			=> '',
			'portfolio_cats'	=> '',
			'product_cats'		=> '',
			'img_format'		=> 'as-landscape',			
			'total_items'		=> 6,
			'filters'			=> '',
			'slider_navig'		=> true,
			'slider_pagin'		=> true,
			'slider_auto'		=> 5000,
			'transition'		=> 'none',
			'css_classes'		=> '',
		);
		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		
		?>

		
		<div class="description fourth">
		
			<label for="<?php echo $this->get_field_id('img_format') ?>"> Image format</label><br/>
			
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
		</div>
		
		<div class="description fourth">
		
			<label for="<?php echo $this->get_field_id('post_type') ?>">Post type</label><br/>
			<?php
			$post_types = array(
				'post'		=> 'Post',
				'portfolio'	=> 'Portfolio',
				'product'	=> 'Products'
				);
			echo aq_field_select('post_type', $block_id, $post_types, $post_type); 
			?>

		</div>
		
		<div class="description fourth">
		
			<label for="<?php echo $this->get_field_id('style') ?>">Layout style</label><br/>
			<?php
			$styles = array(
				'style1'	=> 'Style 1',
				'style2'	=> 'Style 2',
				'style3'	=> 'Style 3',
				'style4'	=> 'Style 4'
				);
			echo aq_field_select('style', $block_id, $styles, $style); 
			?>
			
		</div>
		
		<div class="description fourth last">
			
			<label for="<?php echo $this->get_field_id('anim') ?>">Animation style</label><br/>
			<?php
			$anim_array = array(
				'anim1'		=> 'Style 1',
				'anim2'		=> 'Style 2',
				'anim3'		=> 'Style 3',
				'anim4'		=> 'Style 4 (3d)',
				'no_anim'	=> 'No animation'
				);
			echo aq_field_select('anim', $block_id, $anim_array, $anim); 
			?>
			
			
		</div>
		
		
		<div class="clearfix clear"></div>
		<hr />	
		
		<?php 
		/// GET TAXONOMIES FOR FILTERING
		//
		$is_port_tax	= taxonomy_exists( 'portfolio_category' );
		$is_slider_tax	= taxonomy_exists( 'slider' );
		$is_product_tax	= taxonomy_exists( 'product_cat' );
		?>
		
		<div class="description third">
			<label for="<?php echo $this->get_field_id('post_cats') ?>">
				Post categories:
			</label>	<br/>
				<?php
				$post_cats_arr = array();  
				$post_cats_obj = get_terms('category','hide_empty=1');
				if ($post_cats_obj) {
					foreach ($post_cats_obj as $cat) {
						$post_cats_arr[$cat->slug] = $cat->name ;
						}
				}else{
					$post_cats_arr[0] = '';
				}
				echo aq_field_multiselect('post_cats', $block_id, $post_cats_arr, $post_cats); 
				?>
		</div>
		
		
		<div class="description third">
			
			<?php if( $is_port_tax ) {?>
			
				<label for="<?php echo $this->get_field_id('portfolio_cats') ?>">
					Portfolio categories:
				</label><br/>
				<?php
				$portfolio_cats_arr = array();  
				$portfolio_cats_obj = get_terms('portfolio_category','hide_empty=0');
				if ($portfolio_cats_obj) {
					foreach ($portfolio_cats_obj as $portfolio_cat) {
						$portfolio_cats_arr[$portfolio_cat->slug] = $portfolio_cat->name ;
					}
				}else{
					$portfolio_cats_arr[0] = '';
				}
				echo aq_field_multiselect('portfolio_cats', $block_id, $portfolio_cats_arr, $portfolio_cats); 
				?>
			<?php 
			}else{
				echo '<p class="description">There is no <strong>"Portfolio category"</strong> taxonomy registered.<br /> <br /> Please, install and activate "Aligator Custom Post Types plugin</p>';
			}
			?>
				
		</div>	
		
		
		<div class="description third last">
		
			<?php if( $is_product_tax ) { ?>
			<label for="<?php echo $this->get_field_id('product_cats') ?>">Product categories:</label><br/>
				
			<?php
				$product_cats_arr = array();  
				$product_cats_obj = get_terms('product_cat','hide_empty=1');
				if ($product_cats_obj) {
					foreach ($product_cats_obj as $product_cat) {
						$product_cats_arr[$product_cat->slug]= $product_cat->name ;
					}
				}else{
					$product_cats_arr = array();
				}
				echo aq_field_multiselect('product_cats', $block_id, $product_cats_arr, $product_cats); 
			
			}else{
				echo '<p class="description">There is no <strong>"Product category"</strong> taxonomy registered.<br /> <br /> Please, install and activate "WooCommerce" plugin</p>';
			}
			
			?>
		</div>	
		

		<div class="clearfix clear"></div>
		<hr />	
		
		<div class="description half">
			
			<label for="<?php echo $this->get_field_id('filters') ?>">Special filters</label><br />
			<?php
			$filters_array = array(
				'latest'		=> 'Latest',
				'featured'		=> 'Featured',
				'best_sellers'	=> 'Best selling (only WC products)',
				'best_rated'	=> 'Best rated (only WC products)'
				);
			echo aq_field_select('filters', $block_id, $filters_array, $filters); 
			?>
		
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('total_items') ?>">Total items to display</label><br>
			<?php echo aq_field_input('total_items', $block_id, $total_items, $size = 'min') ?>
			
			
			<hr>
				
			<label for="<?php echo $this->get_field_id('transition') ?>">CSS transitions</label><br />
			<?php 
			$transitions = array(
				'none'		=> 'None',
				'fade'		=> 'Fade',
				'backSlide'	=> 'Back Slide',
				'goDown'	=> 'Go Down',
				'fadeUp'	=> 'Fade Up',
				);
			echo aq_field_select('transition', $block_id, $transitions, $transition) ?>
		
		</div>
		
			
		
		<div class="description half last">
			
			<label for="<?php echo $this->get_field_id('slider_pagin') ?>">Slider pagination</label><br />
			
			<?php echo aq_field_checkbox('slider_pagin', $block_id, $slider_pagin); ?>
			
			<div class="clearfix clear"></div>
			<label for="<?php echo $this->get_field_id('slider_navig') ?>">Slider navigation</label><br />
			
			<?php echo aq_field_checkbox('slider_navig', $block_id, $slider_navig); ?>
		
			<div class="clearfix clear"></div>		
			
			<label for="<?php echo $this->get_field_id('slider_auto') ?>">Slides autoplay delay time</label><br />
			<?php echo aq_field_input('slider_auto', $block_id, $slider_auto, $size = 'full', $type = "number" ); ?>
			
			<p class="description">Use the miliseconds ( 1second = 1000 miliseconds; example: 100 for 1/10th of second ). Leave empty for no autoplay.</p>
			
			
		</div>
		
		<hr>
		
		<div class="description">	
			<label for="<?php echo $this->get_field_id('css_classes') ?>">Additional CSS classes</label>
			<?php echo aq_field_input('css_classes', $block_id, $css_classes, $size = 'full') ?>
		
			<p class="description small">Add custom css classes, defined in child theme css file, or custom css (in Theme options). Multiple classes can be added, separated by space.</p>
		</div>	
		
		<hr>		
		
	<?php
	
	}// end function form
	
	function block($instance) {
		
		global $post, $sequoia_woo_is_active;
		
		if( $sequoia_woo_is_active ) {
			
			global  $product, $woocommerce_loop, $wp_query, $woocommerce;
			
		}		
		
		extract($instance);
		
		
		// POSTS, PORTFOLIO OR PRODUCT FILTER ARGS
		$order_rand	= false;
		$args_filters = array();
		if ( $post_type == 'post'  ) {
		
			if ( $filters == 'featured' ){
				$sticky_array = get_option( 'sticky_posts' );
				$args_filters = array('post__in' => $sticky_array);
			}
		
		}elseif ( $post_type == 'portfolio' ){
		
			if ( $filters == 'featured' ){
				$args_filters = array( 
					'meta_key' => 'as_featured_item',
					'meta_value' => 1
				);
			}
			
		}elseif( $post_type = 'product' ){
		
			// PRODUCT FILTERS:
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
			// end product filters
		
		}else{
		
			$args_filters = array();
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

		// if there are taxonomies selected, turn on taxonomy filter:
		if( !empty($tax_terms) ) {

			$tax_filter_args = array('tax_query' => array(
								array(
									'taxonomy' => $taxonomy,
									'field' => 'slug', // can be 'slug' or 'id'
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
			'post_status' => 'publish',
			'post_type' => $post_type,
			'post_parent' => 0,
			'suppress_filters' => false,
			'orderby'     => 'menu_order date',
			'order'       => 'ASC',
			'numberposts' => $total_items
		);
		
		$all_args = array_merge( $main_args, $args_filters, $tax_filter_args );
		
		$content = get_posts($all_args);
		
		####################  HTML STARTS HERE: ###########################
		echo $css_classes ? '<div class="'.esc_attr($css_classes).'">' : null;
		?>
			
						
		<div id="carousel-<?php echo $block_id; ?>" class="slider">
			
			<input type="hidden" class="slides-config" data-navigation="<?php echo $slider_navig; ?>" data-pagination="<?php echo $slider_pagin; ?>" data-auto="<?php echo $slider_auto; ?>"  <?php echo ($transition != 'none') ? 'data-trans="'.$transition.'"' : ''; ?> />
			
			<div class="owlcarousel-slider">

				<?php 			
				foreach ( $content as $post ) {
				
					setup_postdata( $post );
					$id = get_the_ID();
					$post_type = get_post_type();
				?>

				<div class="slide-item<?php echo ' '. $style; echo ' '. $anim; ?>">
				
					<?php echo as_image( $img_format ); ?>
					
					<div class="text">
						
						<?php 
						$p_meta		= get_post_meta( $id, '_portfolio_meta_box');
						$tagline	= get_post_meta( $id, 'as_tagline', true);
						
						echo '<a href="'.esc_url(get_permalink()).'"><h2>'. esc_html(get_the_title()) .'</h2>';
	
						($sequoia_woo_is_active && $post_type=='product') ? woocommerce_template_loop_price() : '';
						
						echo '</a>';
						
						echo ($tagline || get_the_content()) ? '<div class="addendum">' : '';
						
						echo $tagline ? '<h4>'. esc_html($tagline) .'</h4>' : '';
						
						echo get_the_content() ? '<p>' . apply_filters('as_custom_excerpt', 50, false ) . '</p>' : '';
						
						echo ($tagline || get_the_content()) ? '</div>' : '';
						
						?>
						
					</div>

				
				</div><!-- /.slide-item -->
				
				
				<?php }// END foreach ?>
				
			</div> <!-- /.slider-block .owl-carousel -->


		
		</div><!-- /.carousel -->
		
		<?php
		####################  HTML ENDS HERE: ###########################
		echo $css_classes ? '</div>' : null;
		?>
		
		
		<div class="clearfix"></div>
		
	<?php
		
	
	}/// END func block
	
		
	function update($new_instance, $old_instance) {
		return $new_instance;
	}
	
	
}?>