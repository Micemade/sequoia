<?php
/**
 *	AS_Product_Categories.
 *
 *	block and class for displaying product categories.
 */
class AS_Product_Categories extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Product categories',
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('as_product_categories', $block_options);
	}
	
	function animations_array() {
			
		include ( get_template_directory() .'/inc/functions/animations-icons-arrays.php' );
		return $block_enter_anim_arr;

	}
	
	function form($instance) {
		
		$sequoia_wc_active = apply_filters( 'sequoia_wc_active', '' );
		
		$defaults = array(
			'title'			=> '',
			'subtitle'		=> '',
			'sub_position'	=> 'bellow',
			'title_style'	=> 'center',
			'enter_anim'	=> 'fadeIn',
			'img_width'		=> 300,
			'img_height'	=> 180,
			'prod_cat_menu'	=> 'images',
			'menu_columns'	=> 'stretch',
			'text_color'	=> '',
			'overlay_color'	=> '',
			'product_cats'	=> '',
			'css_classes'	=> '',
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
			<label for="<?php echo $this->get_field_id('title_style') ?>">Block title style</label><br/>
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
		
			<label for="<?php echo $this->get_field_id('enter_anim') ?>">Block animation</label><br/>	
			
			<?php echo aq_field_select('enter_anim', $block_id, $this->animations_array(), $enter_anim ); ?>
			
		</div>
		
		<hr>
		
		<div class="description third">
			
			<label for="<?php echo $this->get_field_id('product_cats') ?>">Product categories:</label><br/>
			<?php
			if( $sequoia_wc_active ) {
				$product_cats_arr = array();  
				$product_cats_obj = get_terms('product_cat','hide_empty=0&hierarchical=true');
				if ($product_cats_obj) {
					foreach ($product_cats_obj as $product_cat) {
						$product_cats_arr[$product_cat->slug]= $product_cat->name ;
					}
				}else{
					$product_cats_arr = array();
				}
				echo aq_field_multiselect('product_cats', $block_id, $product_cats_arr, $product_cats); 
			}else{
				echo '<p class="description">WooCommerce plugin is not active. Please, activate it to use product categories.</p>';
			}
			?>
			
			
		</div>
		
		
		<div class="description third">
		
			
			<label for="<?php echo $this->get_field_id('prod_cat_menu') ?>">Categories menu</label><br />
			<?php
			$prod_cat_menu_arr = array(
				'images'		=> 'With category images',
				'no_images'		=> 'Without category images',
				);
			echo aq_field_select('prod_cat_menu', $block_id, $prod_cat_menu_arr, $prod_cat_menu); 
			?>
			
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('menu_columns') ?>">Menu columns</label><br />
			<?php
			$menu_columns_arr = array(
				'stretch'	=> 'Auto stretch',
				'auto'		=> 'Auto float',
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
			<?php echo aq_field_color_picker('text_color', $this->block_id, $text_color, $defaults['text_color']) ?>
			
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('overlay_color') ?>"><?php echo __('Category image overlay color','sequoia'); ?>
			</label><br />
			<?php echo aq_field_color_picker('overlay_color', $this->block_id, $overlay_color, $overlay_color) ?>
			
		</div>
		
		
		<div class="description third last">			
					
			<label for="<?php echo $this->get_field_id('img_width') ?>">Image width</label><br />
			<?php echo aq_field_input('img_width', $block_id, $img_width, $size = 'min');	?>
			
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('img_height') ?>">Image height</label><br />
			<?php echo aq_field_input('img_height', $block_id, $img_height, $size = 'min');	?>
			
			<div class="clearfix"></div>
		
		</div>
		
		<hr>
		
		<div class="description">	
			<label for="<?php echo $this->get_field_id('css_classes') ?>">Additional CSS classes</label>
			<?php echo aq_field_input('css_classes', $block_id, $css_classes, $size = 'full') ?>
		
			<p class="description small">Add custom css classes, defined in child theme css file, or custom css (in Theme options). Multiple classes can be added, separated by space.</p>
		</div>	
		
		<div class="clearfix clear"></div>
		
	<?php
	
	}

	
	function block($instance) {
		
		global $post, $product, $woocommerce_loop, $wp_query, $woocommerce;
		
		$sequoia_wc_active = apply_filters( 'sequoia_wc_active',"");
		
		extract($instance);
		
		if ( $enter_anim != 'none' && !wp_style_is( 'animate', 'enqueued' )) {
				
			wp_register_style( 'animate', get_template_directory_uri() . '/css/animate.css');
			wp_enqueue_style( 'animate' );
			
		}
		
		
		if( $sequoia_wc_active ) {
				
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
		echo $css_classes ? '<div class="'.esc_attr($css_classes).'">' : null
		?>
		
		<div class="header-holder <?php echo $title_style; ?>">
		<?php 
		// DISPLAY BLOCK TITLE AND "SUBTITLE":
		echo ( $subtitle && $sub_position == 'above' ) ? '<div class="block-subtitle above '. $title_style .'">' . $subtitle . '</div>' : ''; 
		
		echo $title ? '<h2 class="categories-block block-title '. $title_style .'">' . $title . '</h2>' : '';
		
		echo ( $subtitle && $sub_position == 'bellow' ) ? '<div class="block-subtitle bellow '. $title_style .'">' . $subtitle . '</div>' : ''; 
		?>
		</div>
		
		
		<?php if( $tax_terms ) { 
		/**
		 * THIS GIVES VALIDATOR.V3.ORG ERRORS, BUT NO BROWSER COMPLAINS:
		 * - added right before target element - h4 and .item-overlay
		
		if ( $text_color || $overlay_color ) {
			echo '<style scoped>';
			echo $text_color ? '#prod-cats-'.$block_id.' ul .category-image .term h4 { color: '.$text_color.';}' : null;
			echo $overlay_color ? '#prod-cats-'.$block_id.' ul .category-image a .item-overlay { background-color: '.$overlay_color.';}' : null;
			
			echo '</style>';
		}
		*/
		echo '<ul class="taxonomy-menu cat-images" id="prod-cats-'.$block_id.'">';
		
		if ( $text_color ) {
			echo '<style scoped>';
			echo $text_color ? 'ul#prod-cats-'.$block_id.' .category-image .term h4 { color: '.$text_color.';}' : null;
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
		
		$img_width	= $img_width ? $img_width : 300;
		$img_height = $img_height ? $img_height : 180;
		
		// DISPLAY TAXONOMY MENU:
		$i = 1;
		$anim = ($enter_anim != 'none') ? ' to-anim' : '';
		
		foreach ( $term_Objects as $term_obj ) {
			
			$term_link = get_term_link( $term_obj->slug, 'product_cat' );
			
			if( $prod_cat_menu == 'images' ) { // if images should be displayed:
			
				$thumbnail_id = get_woocommerce_term_meta( $term_obj->term_id, 'thumbnail_id' );
				$image = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail' );

				$params = array( 'width' => $img_width, 'height' => $img_height );
				
				if ( $image ) {
		
					echo '<li id="cat-'.$term_obj->term_id .'" class="category-image'. $grid_cat .' as-hover '. $anim.'" data-i="'.$i.'" >';
					echo '<a href="'.$term_link.'" class="'.$term_obj->slug .'" data-id="'. $term_obj->slug .'">';
					
					echo '<div class="item-overlay">';
					if ( $overlay_color ) {
						echo '<style scoped>';
						echo $overlay_color ? '#prod-cats-'.$block_id.' ul .category-image a .item-overlay { background-color: '.$overlay_color.';}' : null;
						echo '</style>';
					}
					echo '</div>';
					
					echo '<div class="term"><div class="table"><div class="tablerow"><div class="tablecell">';
					
					echo '<h4 class="box-title">' . $term_obj->name . '</h4>';
					
					echo '</div></div></div></div>';

					echo '<img src="' . bfi_thumb( $image[0], $params ). '" alt="" />';
					echo '<div class="arrow-down"></div></a>';
					echo '</li>';
					
				}else{
				
					echo '<li id="cat-'.$term_obj->term_id .'" class="category-image'. $grid_cat .' as-hover '.$anim .'" data-i="'.$i.'" >';
					echo '<a href="'.$term_link.'" class="'.$term_obj->slug .'" data-id="'. $term_obj->slug .'">';
					
					echo '<div class="item-overlay"></div>';
					
					echo '<div class="term"><div class="table"><div class="tablerow"><div class="tablecell">';
					
					if ( $text_color ) {
						echo '<style scoped>';
						echo $text_color ? '#prod-cats-'.$block_id.' ul .category-image .term h4 { color: '.$text_color.';}' : null;
						echo '</style>';
					}
					
					echo '<h4 class="box-title">' . $term_obj->name . '</h4></div></div></div></div>';
					echo '<img src="' . bfi_thumb( PLACEHOLDER_IMAGE, $params ). '" alt="" />';
					echo '<div class="arrow-down"></div></a>';
					echo '</li>';
				}
				
			}elseif( $prod_cat_menu == 'no_images' ){
			
				echo '<li id="cat-'.$term_obj->term_id .'" class="category-link'. $grid_cat .'">';
				echo '<a href="'.$term_link.'" class="'.$term_obj->slug .'" data-id="'. $term_obj->slug .'">';
				echo '<div class="term">' . $term_obj->name . '</div>';
				echo '</a>';
				echo '</li>';
				
			}
			$i++;
		}
		?>
		
		</ul>
		
		<?php }// endif $tax_terms ?>
		
		<?php
		####################  HTML ENDS HERE: ###########################
		echo $css_classes ? '</div>' : null;
		?>
		
		<script>
		(function( $ ){
			$.fn.anim_waypoints_prod_cat = function(blockId, enter_anim) {
				
				var thisBlock = $('#prod-cats-'+ blockId );
				if ( !window.isMobile && !window.isIE9 ) {
					var item = thisBlock.find('.category-image');
					
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
					thisBlock.find('.category-image').each( function() {
						$(this).removeClass('to-anim');
					});
				}
				
			}
		})( jQuery );
		
		jQuery(document).ready( function($) {
			
			$(document).anim_waypoints_prod_cat("<?php echo $block_id; ?>"," <?php echo $enter_anim;?>");
		
		});
		</script>
		
		
		<?php
	
		}else{
		
			echo '<h5 class="no-woo-notice">' . __('PRODUCT CATEGORIES BLOCK DISABLED.<br> Sorry, it seems like WooCommerce is not active. Please install and activate last version of WooCommerce.','sequoia') . '</h5>';
				return;
		} // if $sequoia_wc_active
	
	}/// END func block
	
	function update($new_instance, $old_instance) {
		return $new_instance;
	}
} ?>