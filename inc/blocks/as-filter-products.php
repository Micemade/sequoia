<?php
/**
 *	AS_Fiter_Categories.
 *
 *	block and class for showing posts, portfolios.
 *	features - filtering and sorting items via jQuery Shuffle plugin
 */
class AS_Filter_Products extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Filtered products',
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('as_filter_products', $block_options);
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
			'post_type'			=> 'product',
			'img_format'		=> 'as-portrait',
			'product_cats'		=> '',
			'filters'			=> 'latest',
			'shop_quick'		=> true,
			'shop_buy_action'	=> true,
			'shop_wishlist'		=> true,
			'zoom_button'		=> true,
			'link_button'		=> true,
			'anim'				=> 'anim-1',
			'data_anim'			=> 'none',
			'tax_menu_style'	=> 'dropdown',
			'sorting'			=> false,
			'custom_img_width'	=> '',
			'custom_img_height'	=> '',
			'total_items'		=> 8,
			'in_row'			=> 4,
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
		
		<?php // POST TYPE AND TAXONOMIES FOR FILTERING:  ?>							
		
		<div class="description fourth">
		
			<label for="<?php echo $this->get_field_id('product_cats') ?>">Product categories *:</label><br/>
				
			<?php
			if( $sequoia_wc_active ) {

				$terms_arr = apply_filters('as_terms', 'product_cat' );
				
				echo aq_field_multiselect('product_cats', $block_id, $terms_arr, $product_cats); 
				
				
			}else{
				echo '<p class="description">WooCommerce plugin is not active. Please, activate it to use product categories.</p>';
			}
			?>
			
			<label for="<?php echo $this->get_field_id('tax_menu_style') ?>">Categories menu style</label><br/>	
			<?php
			$tax_menu_styles = array(
				'inline'	=> 'Inline menu',
				'dropdown'	=> 'Dropdown menu',
				'none'		=> 'None'
				);
			echo aq_field_select('tax_menu_style', $block_id, $tax_menu_styles, $tax_menu_style); 
			?>
			
			<label for="<?php echo $this->get_field_id('sorting') ?>">Sorting dropdown ?</label><br />
			<?php echo aq_field_checkbox('sorting', $block_id, $sorting); ?>
			
			<div class="clearfix"></div><br />
			
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
			
			
		</div>
		
		
		<div class="description fourth">
			
			<?php // IMAGE SETTINGS :?>
			
			<label for="<?php echo $this->get_field_id('img_format') ?>">Image format</label><br/>	
			<?php
			$img_format_array = array(
				'thumbnail'		=> 'Thumbnail',
				'medium'		=> 'Medium',
				'as-portrait'	=> 'Sequoia portrait',
				'as-landscape'	=> 'Sequoia landscape',
				'large'			=> 'Large'
				);
			echo aq_field_select('img_format', $block_id, $img_format_array, $img_format); 
			?>
			
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('custom_img_width') ?>">Custom image width</label><br />
			<?php echo aq_field_input('custom_img_width', $block_id, $custom_img_width, $size="min"); ?>
			
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('custom_img_height') ?>">Custom image height</label><br />
			<?php echo aq_field_input('custom_img_height', $block_id, $custom_img_height, $size="min"); ?>
			
			<p class="description">"Custom image size" overrides "Image Format" (registered image sizes) settings. Use both width and height value.</p>
		
		</div>	
		
		
		<div class="description fourth">	
			

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
		
		<div class="description fourth last">
		
			<label for="<?php echo $this->get_field_id('zoom_button') ?>">Show zoom button ?</label><br />
			<?php echo aq_field_checkbox('zoom_button', $block_id, $zoom_button); ?>
			
			<div class="clearfix clear"></div>
			
			<label for="<?php echo $this->get_field_id('link_button') ?>">Show link button ?</label><br />
			<?php echo aq_field_checkbox('link_button', $block_id, $link_button); ?>
			
			<p class="description">If both zoom and link button are disabled the link to single product will apply to image</p>
			
			<hr>
			
			<label for="<?php echo $this->get_field_id('total_items') ?>">Total items</label><br />
			<?php echo aq_field_input('total_items', $block_id, $total_items, $size="min"); ?>
			
			<p class="description">If empty, all items will e showed.</p>
		
			<div class="clearfix clear"></div>

			<label for="<?php echo $this->get_field_id('in_row') ?>">In one row</label>
			<?php
			$in_row_array = array(
				'1'	=> '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'6' => '6'
				);
			echo aq_field_select('in_row', $block_id, $in_row_array, $in_row);
			?>
		</div>
		
		<hr />
		<p class="description"><strong>* To show items without filtering deselect categories (using CTRL/Command + click). To select multiple, also use CTRL/Command + click.</strong></p>
		
		<hr />
		
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
		
		<p class="description">If either of these two fields are empty, the button "more" button won't show.
		</p>
		
		<hr />
		
		<div class="description">	
			<label for="<?php echo $this->get_field_id('css_classes') ?>">Additional CSS classes</label>
			<?php echo aq_field_input('css_classes', $block_id, $css_classes, $size = 'full') ?>
		
			<p class="description small">Add custom css classes, defined in child theme css file, or custom css (in Theme options). Multiple classes can be added, separated by space.</p>
		</div>
		
	<?php
	
	}
	
	function block($instance) {
		
		global $post, $product, $woocommerce_loop, $wp_query, $woocommerce;
	
		$sequoia_wc_active = apply_filters( "sequoia_wc_active","");
		
		extract($instance);
		
		if ( $enter_anim != 'none' && !wp_style_is( 'animate', 'enqueued' )) {
				
			wp_register_style( 'animate', get_template_directory_uri() . '/css/animate.css');
			wp_enqueue_style( 'animate' );
			
		}
		
		if( $sequoia_wc_active ) {
		
		$grid = round( 12 / $in_row );
		$sticky_array = get_option( 'sticky_posts' );
		$total_items = $total_items ? $total_items : -1;
		
		
		
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
		echo ( $subtitle && $sub_position == 'above' ) ? '<div class="block-subtitle above '. $title_style .' ">' . $subtitle . '</div>' : ''; 
		
		echo $title ? '<h2 class="categories-block block-title '. $title_style .'">' . $title . '</h2>' : '';
		
		echo ( $subtitle && $sub_position == 'bellow' ) ? '<div class="block-subtitle bellow '. $title_style .'">' . $subtitle . '</div>' : ''; 
		?>
		</div>
		
		<div class="shuffle-filter-holder">
		
		<?php 
		if( $tax_terms && $tax_menu_style != 'none') {
				
			echo $tax_menu_style == 'dropdown' ?'<div class="menu-toggler block-tax-toggler"><a href="#" title="'.__('Toggle categories','sequoia').'" class="icon-menu-2"></a></div>' : null;
			?>
			
			<ul class="taxonomy-menu<?php echo $tax_menu_style == 'dropdown' ? ' tax-dropdown' : null; ?> tax-filters">
			
			<li class="all category-link"><a href="#" class="active"><div class="term"><?php echo __('All','sequoia'); ?></div></a></li>
			
			<?php
			foreach ( $tax_terms as $term ) {
				
				$to = get_term_by( 'slug', $term, $taxonomy );
				
				if( !$to  ) {
					continue;
				}
			
				echo '<li class="'. esc_attr( $to->slug ).' category-link" id="cat-'.esc_attr( $to->slug ).'">';
				echo '<a href="#" data-group="'. esc_attr( $to->slug ).'">';
				echo '<div class="term">' . esc_html( $to->name ) . '</div>';
				echo '</a>';
				echo '</li>';
				
			}
			?>
			</ul>
			
			<div class="clearfix"></div>
			
		<?php } // endif $tax_terms ?>

		<?php if( $sorting ) {?>
		<div class="sort-holder">	
			<select class="sort-options">
				<option value=""><?php echo __('Default sorting','sequoia'); ?></option>
				<option value="title"><?php echo __('Sort by Title ','sequoia'); ?></option>
				<option value="date-created"><?php echo __('Sort by Date Created','sequoia'); ?></option>
			</select>
		</div>
		<?php }; ?>
		
		<?php
		// IF CUSTOM IMAGE SIZE ( both width and height required )
		if( $custom_img_width && $custom_img_height ) {
			$img_width = $custom_img_width ? $custom_img_width : 450;
			$img_height = $custom_img_height ? $custom_img_height : 300;
		}else{
			$img_width	= '';
			$img_height = '';
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
		
		$order_random = ($filters == 'random') ? 'rand ' : '';
		
		$main_args = array(
			'no_found_rows'		=> 1,
			'post_status'		=> 'publish',
			'post_type'			=> 'product',
			'post_parent'		=> 0,
			'suppress_filters'	=> false,
			'orderby'			=> $order_rand ? 'rand menu_order date' : 'menu_order date',
			'order'				=> 'DESC',
			'numberposts'		=> $total_items,
			'meta_query' => array(
				array(
					'key' => '_stock_status',
					'value' => array('instock','outofstock'),
					'compare' => 'IN'
				)
			)
		);
		
		$all_args = array_merge( $main_args, $args_filters, $tax_filter_args );

		//$content = get_posts( $all_args );
		$content = new WP_Query( $all_args );
		?>	
			
		<div id="filter-prod-<?php echo $block_id; ?>" class="content-block cb-4 woocommerce">
			
		
			<ul class="category-content shuffle<?php echo ' '.$anim ;?> <?php echo $data_anim == 'none' ? '' : $data_anim; ?>">
			
			<?php 
	
			$i = 1;
			
			if( count( $content ) == 1 || $in_row == 1) {
				$medium_grid = '12';
			}else{
				$medium_grid = '6';
			}
			
			//start products loop
			//foreach ( $content as $post ) {
			if ( $content->have_posts() ) {
				
			while ( $content->have_posts() ) : $content->the_post();					
				
				setup_postdata( $post );
				
				global $product;
				
				/* 
				// Hiding product :
				if ( ! $product || ! $product->is_visible() || !$product->is_in_stock() ) {
					continue;
				} 
				*/
				
				// GET LIST OF ITEM CATEGORY (CATEGORIES) for FILTERING jquery.shuffle
				$terms = get_the_terms( $post->ID, $taxonomy );
				if ( $terms && ! is_wp_error( $terms ) ) : 
					$terms_str = '\'[';
					$t = 1;
					foreach ( $terms as $term ) {
						$zarez = $t >= count($terms) ? '' : ',';
						$terms_str .= '"'. $term->slug . '"' . $zarez; 
						$t++;
					}
					$terms_str .= ']\'';
				else :
					$terms_str = '';
				endif;
				
				if( defined('WPML_ON') ) { // if WPML plugin is active
					$id			= icl_object_id( get_the_ID(), 'product', false, ICL_LANGUAGE_CODE ); 
					$lang_code	= ICL_LANGUAGE_CODE;
				}else{
					$id			= get_the_ID();
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
				if ( $attachment_ids ) {
					$image_url = wp_get_attachment_image_src( $attachment_ids[0], 'full'  );
					$img_url = $image_url[0];
					// IMAGE SIZES:
					/* $imgSizes = all_image_sizes();
					$img_width = $imgSizes[$img_format]['width'];
					$img_height = $imgSizes[$img_format]['height'];
					*/
				}
				// end DATA
				
				$prod_title = '<h4 class="prod-title"><a href="'. $link .'" title="'.esc_attr(get_the_title()).'"> ' . esc_attr(get_the_title()) .'</a></h4>';
				?>
					
				
				<li class="large-<?php echo $grid ? $grid : '6'; ?> medium-<?php echo $medium_grid; ?> small-12 item column" data-id="id-<?php echo $i;?>" <?php echo $terms_str ? 'data-groups='. $terms_str. ''  : null ; ?> data-date-created="<?php echo get_the_date( 'Y-m-d' ); ?>" data-title="<?php echo esc_attr(get_the_title());?>" data-i="<?php echo $i; ?>">

					<div class="anim-wrap<?php echo ($enter_anim != 'none') ? ' to-anim' : '';  ?>">
					
					<?php echo (!$zoom_button && !$link_button) ? '<a href="'.$link.'" title="'. esc_attr(get_the_title()) .'">' : ''; ?>
						
					<div class="item-img">
						
						<div class="front">
														
							<?php function_exists('woocommerce_show_product_loop_sale_flash') ? woocommerce_show_product_loop_sale_flash() : '';
							
							echo as_image( $img_format, $img_width, $img_height );
							
							?>
						
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
								echo as_image( $img_format, $img_width, $img_height );
							}
								
							echo '<div class="back-buttons">';
															
							echo $zoom_button ? '<a href="'. as_get_full_img_url() .'" class="button tiny magnificpopup mfp-image"  title="'.esc_attr(strip_tags(get_the_title())).'"><span class="icon icon-zoom-in" aria-hidden="true"></span></a>' : null;
							
							echo $link_button ? '<a href="'.$link.'" class="button tiny" title="'. esc_attr(get_the_title()) .'"><div class="icon icon-link" aria-hidden="true"></div></a>' : null;
							
							echo '</div>';
							
							?>
						
						</div>
						
					</div>
					
					<?php echo (!$zoom_button && !$link_button) ? '</a>' : ''; ?>
						
					<div class="clearfix"></div>

					
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
							
							if( $shop_wishlist ) {
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
					
					</div><!-- .anim-wrap -->
					
				</li>
				
				<?php 
				$i++;
			
			endwhile; // END while
			
			}// END if
			wp_reset_postdata();
			//wp_reset_query();
			
			?>
			</ul>
					
			<?php if( $more_link_text && $more_link_url ) { ?>
			<div class="bottom-block-link">
			
				<a href="<?php echo $more_link_url ; ?>" title="<?php echo esc_attr($more_link_text); ?>" class="button" <?php echo $target ? 'target="_blank"' : null; ?>>
					<?php echo esc_html($more_link_text); ?>
				</a>
				
			</div>
			<?php } //endif; $more_link_text ?>
			
			<div class="clearfix"></div>
			
		</div><!-- .content-block .cb-4 -->
		
		</div><!-- // end div.shuffle-filter-holder -->
		
		<?php
		####################  HTML ENDS HERE: ###########################
		echo $css_classes ? '</div>' : null;
		?>
		
		<script>
		(function( $ ){
			$.fn.anim_waypoints_filter_prod = function(blockId, enter_anim) {
				
				var thisBlock = $('#filter-prod-'+ blockId );
				if ( !window.isMobile && !window.isIE9 ) {
					
					var item = thisBlock.find('.item');
					
					item.waypoint(
					
						function(direction) {
						
							var item_wrap = $(this).find('.anim-wrap');
							
							if( direction === "up" ) {
							
								item_wrap.removeClass('animated '+ enter_anim).addClass('to-anim');
								
							}else if( direction === "down" ) {
							
								var i =  $(this).attr('data-i');
								setTimeout(function(){
								   item_wrap.addClass('animated '+ enter_anim).removeClass('to-anim');
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
			
			$(document).anim_waypoints_filter_prod("<?php echo $block_id; ?>"," <?php echo $enter_anim;?>");
		
		});
		</script>
		
		<?php
	
		}else{
			echo '<h5 class="no-woo-notice">' . __('FILTERED PRODUCTS BLOCK DISABLED.<br> Sorry, it seems like WooCommerce is not active. Please install and activate last version of WooCommerce.','sequoia') . '</h5>';
				return;
		} // if $sequoia_wc_active
	
	}/// END func block
	
	function update($new_instance, $old_instance) {
		return $new_instance;
	}
} ?>