<?php

if(!class_exists('AS_Images_Slider')) {

	class AS_Images_Slider extends AQ_Block {
	
		function __construct() {
		
			$block_options = array (
				'name' => 'Images slider',
				'size' => 'span12',
			);
			
			//create the widget
			parent::__construct('AS_Images_Slider', $block_options);
			
			//add ajax functions
			add_action('wp_ajax_aq_block_image_add_new', array($this, 'add_image'));
			
		}
		
		function animations_array() {
			
			include ( get_template_directory() .'/inc/functions/animations-icons-arrays.php' );
			return $block_enter_anim_arr;

		}
		
		function form($instance) {
		
			$defaults = array(
				'main_title'	=> '',
				'title_style'	=> 'center',
				'items'			=> array(
									1 => array(
										'image'		=> '',
										'title'		=> 'New Image',
										'content'	=> '',
										'name'	=> ''
										
									)
								),
				'img_format'	=> 'thumbnail',
				'image_shape'	=> 'square',
				'slider_navig'	=> true,
				'slider_pagin'	=> true,
				'slider_timing'	=> '',
				'transition'	=> 'none',
				'items_desktop'	=>  4,
				'items_desktop_small' => 3,
				'items_tablet'	=>	2,
				'items_mobile'	=> 1,
				'enter_anim'	=> 'fadeIn',
				'css_classes'	=> '',
			);
			
			$instance = wp_parse_args($instance, $defaults);
			extract($instance);
			
			?>
			
			<div class="description third">
					
				<label for="<?php echo $this->get_field_id('main_title') ?>">Images Slider Block Title</label> <br/>
						
				<?php echo aq_field_input('main_title', $block_id, $main_title) ?>
					
			</div>
			
			<div class="description third">
				
				<label for="<?php echo $this->get_field_id('title_style') ?>">Title style</label><br/>
				<?php
				$title_style_arr = array(
					'center'		=> 'Center',
					'float_left'	=> 'Float left',
					'float_right'	=> 'Float right'
					);
				echo aq_field_select('title_style', $block_id, $title_style_arr, $title_style); 
				?>	
			
			</div>
			
			<div class="description third last">
		
				<label for="<?php echo $this->get_field_id('enter_anim') ?>">Viewport enter animation</label><br/>	
				
				<?php echo aq_field_select('enter_anim', $block_id, $this->animations_array(), $enter_anim ); ?>
				
			</div>
			
			<hr>
			
			<div class="description cf">
				
				<ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
					<?php
					$items = is_array($items) ? $items : $defaults['items'];
					$count = 1;
					foreach($items as $item) {	
						$this->create_item($item, $count);
						$count++;
					}
					?>
				</ul>
				
				<a href="#" rel="image" class="aq-sortable-add-new button">Add new image</a>
				
				
			</div>
			
			<hr>
			
			<div class="description half">
				
				<label for="<?php echo $this->get_field_id('img_format') ?>">Images format</label><br/>
				
				<?php
				$img_format_array = array(
					'thumbnail'		=> 'Thumbnail',
					'medium'		=> 'Medium',
					'as-portrait'	=> 'Sequoia portrait',
					'as-landscape'	=> 'Sequoia landscape',
					'large'			=> 'Large',
					'full'			=> 'Full'
					);
				echo aq_field_select('img_format', $block_id, $img_format_array, $img_format); 
				?>	
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('image_shape') ?>">Images shape</label><br/>
				
				<p class="description">Applicable to "thumbnail" image format</p>
				
				<?php 
				$img_styles = array(
					'square'=> 'Square',
					'round' => 'Round',
					);
				echo aq_field_select('image_shape', $block_id, $img_styles, $image_shape) ?>
				
			</div>
			
			<div class="description half last">
			
				<label for="<?php echo $this->get_field_id('slider_pagin') ?>">Slider pagination</label><br />
				<?php echo aq_field_checkbox('slider_pagin', $block_id, $slider_pagin); ?>
				
				<div class="clearfix clear"></div>
						
				<label for="<?php echo $this->get_field_id('slider_navig') ?>">Slider navigation</label><br />
				<?php echo aq_field_checkbox('slider_navig', $block_id, $slider_navig); ?>
				<div class="clearfix clear"></div>
					
				<label for="<?php echo $this->get_field_id('slider_timing') ?>">Slider timing</label><br />
				<?php echo aq_field_input('slider_timing', $block_id, $slider_timing, $size = 'min');	?>
			

				<p class="description">Timing for auto sliding. Use the miliseconds ( 1second = 1000 miliseconds; example: 100 for 1/10th of second ).If left empty the slider won't auto slide.</p>
				<hr>
				
				<label for="<?php echo $this->get_field_id('transition') ?>">Slider CSS transitions</label><br />
				<?php 
				$transitions = array(
					'none'		=> 'None',
					'fade'		=> 'Fade',
					'backSlide'	=> 'Back Slide',
					'goDown'	=> 'Go Down',
					'fadeUp'	=> 'Fade Up',
					);
				echo aq_field_select('transition', $block_id, $transitions, $transition) ?>
				
				<p class="description">NOTE: CSS transitions will automatically set item to 1, or to single item per slide.</p>
			
			</div>
			
			<hr>
			
			<p class="description"><strong>RESPONSIVE SETTINGS</strong> - set number of items for responsiveness (adaptive to mobile devices ):</p>
			
			<div class="description fourth">
			
				<label for="<?php echo $this->get_field_id('items_desktop') ?>">Items in desktop width</label><br />
				<?php echo aq_field_input('items_desktop', $block_id, $items_desktop, $size="min"); ?>
			
			</div>
			
			<div class="description fourth">
			
				<label for="<?php echo $this->get_field_id('items_desktop_small') ?>">Items in desktop smaller</label><br />
				<?php echo aq_field_input('items_desktop_small', $block_id, $items_desktop_small, $size="min"); ?>
			
			</div>
			
			<div class="description fourth">
			
				<label for="<?php echo $this->get_field_id('items_tablet') ?>">Items in tablet view</label><br />
				<?php echo aq_field_input('items_tablet', $block_id, $items_tablet, $size="min"); ?>
			
			</div>
			
			<div class="description fourth last">
			
				<label for="<?php echo $this->get_field_id('items_mobile') ?>">Items in mobile view</label><br />
				<?php echo aq_field_input('items_mobile', $block_id, $items_mobile, $size="min"); ?>
			
			</div>
			
			<hr>
		
			<div class="description">	
				<label for="<?php echo $this->get_field_id('css_classes') ?>">Additional CSS classes</label>
				<?php echo aq_field_input('css_classes', $block_id, $css_classes, $size = 'full') ?>
			
				<p class="description small">Add custom css classes, defined in child theme css file, or custom css (in Theme options). Multiple classes can be added, separated by space.</p>
			</div>	
			
			<?php
		}
		
		function create_item($item = array(), $count = 0) {
				
			?>
			<li id="<?php echo $this->get_field_id('items') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">
				
				<div class="sortable-head cf">
					<div class="sortable-title">
						<strong><?php echo $item['title'] ?></strong>
					</div>
					<div class="sortable-handle">
						<a href="#">Open / Close</a>
					</div>
				</div>
				
				<div class="sortable-body">
					
					<div class="tab-desc description half">
				
						<div class="screenshot member-image">
						
							<input type="hidden" class="placeholder" value="<?php echo PLACEHOLDER_IMAGE; ?>" />
							<a href="#" class="remove-media"><img src="<?php echo get_template_directory_uri(); ?>/admin/images/icon-delete.png" /></a>
							<?php
							if( !empty($item['image']) ) {					
								$imgurl = wp_get_attachment_image_src( $item['image'], 'thumbnail' );
								echo '<img src="'. $imgurl[0] .'" class="att-image" />';
							}else{
								echo '<img src="'. PLACEHOLDER_IMAGE .'" class="att-image" />';
							}
							?>
							
						</div>
						<br />
						
						<?php $thisID = $this->get_field_id('items') . '-' . $count .'-image'; ?>
						
						<label for="<?php echo $thisID; ?>">
							
							<?php //echo as_field_upload( $thisID , $thisID, $item['image'], 'thumbnail'); ?>
							<input type="hidden" id="<?php echo $thisID; ?>" class="input-full input-upload" value="<?php echo $item['image'] ?>" name="<?php echo $this->get_field_name('items') ?>[<?php echo $count ?>][image]" data-size="thumbnail">
						
			
							<a href="#" class="aq_upload_button button" rel="image">Upload</a>
						
						
						</label>
						
					</div>	
			
					<div class="tab-desc description half last">
					
						<label for="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-title">
							Image title<br/>
							<input type="text" id="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('items') ?>[<?php echo $count ?>][title]" value="<?php echo $item['title'] ?>" />
						</label>
					
					<div class="clearfix"></div>
					
						<label for="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-content">
							Image caption<br/>
							<textarea id="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-content" class="textarea-full" name="<?php echo $this->get_field_name('items') ?>[<?php echo $count ?>][content]" rows="5"><?php echo $item['content'] ?></textarea>
						</label>
					</div>
				
					
					<p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
					
				</div>
				
			</li>
			<?php
		}
		
		function block($instance) { // frontend output
			
			global $border_decor;
			
			extract($instance);
						
			if ( $enter_anim != 'none' && !wp_style_is( 'animate', 'enqueued' )) {
				
				wp_register_style( 'animate', get_template_directory_uri() . '/css/animate.css');
				wp_enqueue_style( 'animate' );
				
			}
			
			$output = '';
			
			$output .= '<div id="img-slides-'. $block_id .'" class="aq_block_items content-block '.$css_classes.'">';
				
				$output .= '<div class="header-holder '. $title_style.'">';
				
				$output .= $main_title ? '<h2 class="block-title '.$title_style .'">'.$main_title.'</h2>' : '';
				
				$output .= '</div>';
				
				$output .= '<input type="hidden" class="simpleslides-config" data-navigation="'. $slider_navig .'" data-pagination="'. $slider_pagin .'" data-auto="'.$slider_timing.'" data-desktop="'.$items_desktop.'" data-desktop-small="'.$items_desktop_small.'" data-tablet="'.$items_tablet.'" data-mobile="'.$items_mobile.'" '. (($transition != 'none') ? 'data-trans="'.$transition.'"' : '') . ' />';
				
				$output .= '<div class="image-slides'. (count($items) > 1 ? ' simpleslides' : '') .'">';
				
				$i = 1;
				$anim = ($enter_anim != 'none') ? ' to-anim' : '';
				
				foreach( $items as $item ) {
					
					$img = $item['image'];
					
					$output .= '<div class="single-slide '. $image_shape .' column item '.$anim.'" data-i="'.$i.'"><div class="item-img">';
					
					if( $item['image'] ) { 
					$output .= '<div class="front">';
						
						$attr = array(
							'class' => 'attachment-image',
							'title'	=> $item['title'] ? $item['title'] : '',
							'alt'	=> $item['title'] ? $item['title'] : ''
						);
						
						$output .= wp_get_attachment_image( $img, $img_format, false,  $attr ); 

						$output .= '</div>';
					};
					
					$output .= '<div class="back"><div class="item-overlay">';
					
					$output .= '<div class="content" '. ( !$img ? 'style="width: 100%;"' : '' ).'>';
					$output .= ( $item['title'] && $item['title'] != 'New Image' ) ? '<h4>' .$item['title'] .'</h4>' : '';	
					$output .= '<p>'. $item['content'] .'</p>';
					
					$output .= '</div>'; // content
					
					$output .= '</div></div>'; // back, item-overlay
					
					$output .= '</div></div>'; // single-slide
					$i++;
				}
				
				$output .= '</div>';
				
			
			$output .= '</div>';
			
			echo $output;
			if( $enter_anim != 'none' ) {
			?>
		
		<script>
		(function( $ ){
			$.fn.anim_waypoints_img_slides = function(blockId, enter_anim) {
				
				var thisBlock = $('#img-slides-'+ blockId );
				if ( !window.isMobile && !window.isIE9 ) {
					var item = thisBlock.find('.single-slide');
					
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
					thisBlock.find('.single-slide').each( function() {
						$(this).removeClass('to-anim');
					});
				}
				
			}
		})( jQuery );
		
		jQuery(document).ready( function($) {
			
			$(document).anim_waypoints_img_slides("<?php echo $block_id; ?>"," <?php echo $enter_anim;?>");
		
		});
		</script>
		
		<?php 
		
			}// if enter_anim
		} /// end function block
		
		
		/* AJAX add tab */
		function add_image() {
			$nonce = $_POST['security'];
			if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');
			
			$count = isset($_POST['count']) ? absint($_POST['count']) : false;
			$this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';
			
			//default key/value for the tab
			$image = array(
				'image'		=> '',
				'title'		=> 'New Image',
				'content'	=> '',
				'name'	=> ''
			);
			
			if($count) {
				$this->create_item($image, $count);
			} else {
				die(-1);
			}
			
			die();
		}
		
		function update($new_instance, $old_instance) {
			$new_instance = aq_recursive_sanitize($new_instance);
			return $new_instance;
		}
	}
}
