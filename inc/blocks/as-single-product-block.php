<?php

class AS_Single_Product_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Single Product',
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('as_single_product_block', $block_options);
	}
	
	function form($instance) {
		
		$sequoia_wc_active = apply_filters( 'sequoia_wc_active',"");
		
		if ( $sequoia_wc_active ) { 
		
		$defaults = array(
			'title'				=> '',
			'subtitle'			=> '',
			'sub_position'		=> 'bellow',
			'title_style'		=> 'center',
			'img_format'		=> 'medium',
			'slider_navig'		=> true,
			'slider_pagin'		=> true,
			'slider_timing'		=> '',
			'transition'		=> '',
			'block_style'		=> 'images_right',
			'back_color'		=> '#e8e8e8',
			'opacity'			=> '100',
			'product_options'	=> 'reduced',
			'single_product'	=> '',
			'hide_wishlist'		=> false,
			'hide_addtocart'	=> false,
			'css_classes'		=> '',
			
		);
		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		
		?>
		<div class="description fourth">
		
			<label for="<?php echo $this->get_field_id('title') ?>">Block title (optional)</label>
				
			<?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
			
		</div>
		
		<div class="description fourth">
		
			<label for="<?php echo $this->get_field_id('subtitle') ?>">Block subtitle (optional)</label>
				
			<?php echo aq_field_input('subtitle', $block_id, $subtitle, $size = 'full') ?>
			
		</div>
		
		<div class="description fourth">
			<label for="<?php echo $this->get_field_id('title_style') ?>">Block title style</label><br/>
			<?php
			$title_styles = array(
				'center'		=> 'Center',
				'float_left'	=> 'Float left',
				'float_right'	=> 'Float right'
				);
			echo aq_field_select('title_style', $block_id, $title_styles, $title_style); 
			?>	
		</div>
		
		<div class="description fourth last">
		
			<label for="<?php echo $this->get_field_id('sub_position') ?>">Position of the subtitle</label><br/>
			
			<?php 
			$sub_options = array(
				'above'		=> 'Above heading',
				'bellow'	=> 'Bellow heading',
			);
			echo aq_field_select('sub_position', $block_id, $sub_options, $sub_position, $block_id); ?>
		
		</div>
		
		<hr>
		
		<div class="description last">
		
			<label for="<?php echo $this->get_field_id('single_product') ?>">Select one of the products :</label><br/>
				<?php
				$args = array(
					'post_type'			=> 'product',
					'posts_per_page'	=> -1,
					'suppress_filters'	=> true
				);
				$products_arr = array();  
				$products_obj = get_posts($args);
				if ( $products_obj ) {
					foreach( $products_obj as $prod ) {
						
						$products_arr[$prod->ID] = $prod->post_title  ;
					}
				}else{
					$products_arr[0] = '';
				}
				echo aq_field_select('single_product', $block_id, $products_arr, $single_product); 
				?>
		</div>
		
		<hr>

		<div class="description third">
			
			<label for="<?php echo $this->get_field_id('img_format') ?>">Image format</label>
			<br/>
			<?php
			$img_format_arr = array(
				'thumbnail'		=> 'Thumbnail',
				'medium'		=> 'Medium',
				'as-portrait'	=> 'sequoia portrait',
				'as-landscape'	=> 'sequoia landscape',
				'large'			=> 'Large'
				);
			echo aq_field_select('img_format', $block_id, $img_format_arr, $img_format); 
			?>
			
			<div class="clearfix clear"></div>
			
			<label for="<?php echo $this->get_field_id('slider_pagin') ?>">Image gallery pagination</label><br />
			<?php echo aq_field_checkbox('slider_pagin', $block_id, $slider_pagin); ?>
			
			<div class="clearfix clear"></div>
					
			<label for="<?php echo $this->get_field_id('slider_navig') ?>">Image gallery navigation</label><br />
			<?php echo aq_field_checkbox('slider_navig', $block_id, $slider_navig); ?>
			
			<div class="clearfix clear"></div>
					
			<label for="<?php echo $this->get_field_id('slider_timing') ?>">Image gallery timing</label><br />
			<?php echo aq_field_input('slider_timing', $block_id, $slider_timing, $size = 'min');	?>
			
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
		
		<div class="description third">
		
			<label for="<?php echo $this->get_field_id('block_style') ?>">Block style</label>
			<br/>
			<?php
			$block_style_arr = array(
				'images_left'	=> 'Images left',
				'images_right'	=> 'Images right',
				'centered'		=> 'Centered',
				'centered_alt'	=> 'Centered alternate'
				);
			echo aq_field_select('block_style', $block_id, $block_style_arr, $block_style); 
			?>
			
			
			<label for="<?php echo $this->get_field_id('back_color') ?>">Background color
			</label><br />
			<?php echo aq_field_color_picker('back_color', $this->block_id, $back_color, $back_color) ?>
			
			<div class="clearfix clear"></div>
					
			<label for="<?php echo $this->get_field_id('opacity') ?>">Background color opacity</label><br />
			<?php echo aq_field_input('opacity', $block_id, $opacity, $size = 'min');	?> %
		
		
		</div>		
		
		<div class="description third">
			<label for="<?php echo $this->get_field_id('product_options') ?>">
				Product options display
			</label>	<br/>
			<?php
			$options_array = array(
				'reduced'	=> 'Reduced product options',
				'full'		=> 'Full product options'
				
				);
			echo aq_field_select('product_options', $block_id, $options_array, $product_options); 
			?>
			
			<p class="description">Choose to display reduced options (as in products page), or full product options (as in single product page).</p>
			
			<hr>
			
			<label for="<?php echo $this->get_field_id('hide_wishlist') ?>">Hide wishlist</label><br />
			<?php echo aq_field_checkbox('hide_wishlist', $block_id, $hide_wishlist); ?>
			
			<div class="clearfix clear"></div>
			
			<label for="<?php echo $this->get_field_id('hide_addtocart') ?>">Hide "Add to cart"</label><br />
			<?php echo aq_field_checkbox('hide_addtocart', $block_id, $hide_addtocart); ?>
			
		</div>
		
		<hr>
		
		<div class="description">	
			<label for="<?php echo $this->get_field_id('css_classes') ?>">Additional CSS classes</label>
			<?php echo aq_field_input('css_classes', $block_id, $css_classes, $size = 'full') ?>
		
			<p class="description small">Add custom css classes, defined in child theme css file, or custom css (in Theme options). Multiple classes can be added, separated by space.</p>
		</div>	
		
		<hr />	
		
		<?php
	
		}else{
		
			echo '<h5 class="no-woo-notice">' . __('SINGLE PRODUCT BLOCK DISABLED.<br> Sorry, it seems like WooCommerce plugin is not active. Please install and activate last version of WooCommerce.','sequoia') . '</h5>';
		
		} // end if woo_is_active
	
	} // end func. form
	
	
	
	function block($instance) {
		
		global $post, $sequoia_wishlist_is_active, $product, $wp_query, $woocommerce;
		
		$sequoia_wc_active = apply_filters( 'sequoia_wc_active',"");
		
		extract($instance);
		
		if ( $sequoia_wc_active ) {
		
		
		$display_args = array(
			'no_found_rows'		=> 1,
			'post_status'		=> 'publish',
			'post_type'			=> 'product',
			'post_parent'		=> 0,
			'suppress_filters'	=> false,
			'numberposts'		=> 1,
			'include'			=> $single_product
		);
		
		$content = get_posts($display_args);
		
		$opacity = $opacity / 100;
		
		if( $block_style == 'images_right') {
			$arrow_color = 'border-left-color:rgba('.hex2rgb( $back_color ).','.$opacity.')  !important;' ;
		}elseif( $block_style == 'images_left'){
			$arrow_color = 'border-right-color:rgba('.hex2rgb( $back_color ).','.$opacity.')  !important;' ;
		}elseif( $block_style == 'centered'){
			$arrow_color = 'border-bottom-color:rgba('.hex2rgb( $back_color ).','.$opacity.')  !important;' ;
		}elseif( $block_style == 'centered_alt'){
			$arrow_color = 'border-top-color:rgba('.hex2rgb( $back_color ).','.$opacity.')  !important;' ;
		}else{
			$arrow_color = '';
		}
		
		// Enqueue variation scripts
		wp_enqueue_script( 'wc-add-to-cart-variation' );
		
		
		echo '<style type="text/css" scoped>';
		if( $back_color ) {
			if( $block_style == 'centered' || $block_style == 'centered_alt' ) {
				echo '#'.$block_id.' .item-data { background-color: rgba('.hex2rgb( $back_color ).','.$opacity.') !important;}';
			}else{
				echo '#'.$block_id.' { background-color: rgba('.hex2rgb( $back_color ).','.$opacity.') !important;}';
			}
			
			echo '#'.$block_id.'.single-product-block .arrow { '. $arrow_color .' ; opacity: 1 !important; }';
		}
		
		echo '</style>';
		
		####################  HTML STARTS HERE: ###########################
		echo $css_classes ? '<div class="'.esc_attr($css_classes).'">' : null;

		?>
		
		<div class="header-holder <?php echo $title_style; ?>">
		<?php 
		echo ( $subtitle && $sub_position == 'above' )	? '<div class="block-subtitle above '. $title_style .'">' . $subtitle . '</div>' : ''; 
		echo $title		? '<h2 class="block-title '. $title_style .'">' .$title.'</h2>' : '';

		echo ( $subtitle && $sub_position == 'bellow' )	? '<div class="block-subtitle bellow '. $title_style .'">' . $subtitle . '</div>' : ''; 
		?>	
		</div>
		
		<div id="<?php echo $block_id; ?>" class="content-block single-product-block product <?php echo $block_style; ?>">			
			
			<?php 			
			foreach ( $content as $post ) {
			
				setup_postdata( $post );
				
				global $product;
				
				$out_of_stock = '';
				if ( ! $product || ! $product->is_visible() ) {
					continue;
				}elseif( !$product->is_in_stock() ) {
					$out_of_stock = '<span class="out-of-stock">'. __('Out of stock','sequoia') .'</span>';
				}

				$classes = array();	
				$classes = get_post_class();
				$classes[] = 'inner-wrapper item';
				
				echo '<div class="'. implode(' ',$classes).'">';
				
				$id = get_the_ID();
				$link = get_permalink($id);
				
				if( $block_style == 'images_right') {
					$arrow = '<div class="arrow arrow-right"></div>';				
				}elseif( $block_style == 'images_left'){
					$arrow = '<div class="arrow arrow-left"></div>';
				}elseif( $block_style == 'centered'){
					$arrow = '<div class="arrow arrow-up"></div>';
				}elseif( $block_style == 'centered_alt'){
					$arrow = '<div class="arrow arrow-down"></div>';
				}
								
				function_exists('woocommerce_show_product_loop_sale_flash') ? woocommerce_show_product_loop_sale_flash() : '';
				?>					
					
				<?php if( $block_style != 'centered_alt' ) {  ?>
				<div class="images-holder">
								
					<?php echo $out_of_stock ;?>
					
					<?php echo $arrow ;?>
					
					<input type="hidden" class="slides-config" data-navigation="<?php echo $slider_navig; ?>" data-pagination="<?php echo $slider_pagin; ?>" data-auto="<?php echo $slider_timing; ?>" <?php echo ($transition != 'none') ? 'data-trans="'.$transition.'"' : ''; ?> />
					
					<?php do_action( 'do_single_product_images', $img_format );	?>
					
				</div>
				<?php } ?>
				
				<div class="item-data">
				
					<div class="wrap tablecell">
					
						<h3><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($post->post_title); ?></a></h3>
						
						<?php
						if( $product_options == 'reduced' ) { // as in catalog:
						
							echo '<div class="reduced" itemscope>';
							
							if ( $post->post_excerpt ) {
							?>
								<div itemprop="description" class="description">
								
									<?php echo apply_filters( 'woocommerce_short_description', substr( strip_shortcodes($post->post_excerpt), 0, 200 ) . ' ...' )?>
									
								</div>
							
							<?php }
							
							woocommerce_template_loop_price();
							
							echo '<div class="table"><div class="tablerow">	';
							
							// HIDE ADD TO CART
							if( !$hide_addtocart ) {
							
								echo '<div class="item-buttons-holder tablecell">';
									
										do_action( 'woocommerce_after_shop_loop_item' );
									
								echo '</div>';
							}
							
							if( $sequoia_wishlist_is_active && !$hide_wishlist ) {
							
								echo '<div class="item-buttons-holder tablecell">';
								
									do_action( 'as_wishlist_button' );
								
								echo '</div>';
								
							}
							
							echo '</div></div>';// .tablecell .tablerow
							
							echo '</div>'; // .reduced
							
						}else{ // as in single product page: 
						
							do_action('remove_YITH_wishlist_hooks'); // in "woocommerce-theme-edits.php" and "admin_functions.php"
							
							// HIDE ADD TO CART
							if( $hide_addtocart ) {
								remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
							}
							
							// HIDE WISHLIST BUTTON
							if( $hide_wishlist ) {
								remove_action( 'woocommerce_single_product_summary', 'as_wishlist_button_func', 35 );
							}
							
							do_action( 'woocommerce_single_product_summary' );
							
						}
						?>
					
					</div>
				
				</div>
				
				<?php if( $block_style == 'centered_alt' ) {  ?>
				<div class="images-holder">
								
					<?php echo $out_of_stock ;?>
					
					<?php echo $arrow ;?>
					
					<input type="hidden" class="slides-config" data-navigation="<?php echo $slider_navig; ?>" data-pagination="<?php echo $slider_pagin; ?>" data-auto="<?php echo $slider_timing; ?>" <?php echo ($transition != 'none') ? 'data-trans="'.$transition.'"' : ''; ?> />
					
					<?php do_action( 'do_single_product_images', $img_format );	?>
					
				</div>
				<?php } ?>
				
				
				<div class="clearfix"></div>
					
				
			
			<?php }// END foreach ?>

			</div>	
		
		
		</div><!-- /.content-block single-product-block -->
		
		<?php
		####################  HTML ENDS HERE: ###########################
		echo $css_classes ? '</div>' : null;

		
		}else{
			echo '<h5 class="no-woo-notice">' . __('SINGLE PRODUCT BLOCK DISABLED.<br> Sorry, it seems like WooCommerce plugin is not active. Please install and activate last version of WooCommerce.','sequoia') . '</h5>';
				return;
		} // if $sequoia_wc_active
		
	}/// END func block
	
	function update($new_instance, $old_instance) {
		return $new_instance;
	}
} ?>