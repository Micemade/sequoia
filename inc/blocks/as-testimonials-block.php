<?php
/* Aqua Testimonials Block */
if(!class_exists('AS_Testimonials')) {
	
	class AS_Testimonials extends AQ_Block {
	
		function __construct() {
			$block_options = array(
				'name' => 'Testimonials',
				'size' => 'span6',
			);
			
			//create the widget
			parent::__construct('AS_Testimonials', $block_options);
			
			//add ajax functions
			add_action('wp_ajax_aq_block_item_add_new', array($this, 'add_testimonial'));
			
		}
		
		function animations_array() {
			
			include ( get_template_directory() .'/inc/functions/animations-icons-arrays.php' );
			return $block_enter_anim_arr;

		}
		
		function form($instance) {
		
			$defaults = array(
				'main_title'	=> '',
				'title_style'	=> 'center',
				'items'		=> array(
						1 => array(
							'image'		=> '',
							'title'		=> 'New Testimonial',
							'content'	=> '',
							'name'	=> ''
							
						)
					),
				'image_style'		=> 'square',
				'slider_navig'		=> true,
				'slider_pagin'		=> true,
				'slider_timing'		=> '',
				'items_desktop'		=>  2,
				'items_desktop_small' => 2,
				'items_tablet'		=>	1,
				'items_mobile'		=> 1,
				'enter_anim'		=> 'fadeIn',
				'css_classes'		=> ''
			);
			
			$instance = wp_parse_args($instance, $defaults);
			extract($instance);
			
			?>
			
			<div class="description third">
					
				<label for="<?php echo $this->get_field_id('main_title') ?>">Testimonials Block Title </label><br/>
						
				<?php echo aq_field_input('main_title', $block_id, $main_title) ?>
					
			</div>
			
			<div class="description third">
				<label for="<?php echo $this->get_field_id('title_style') ?>">
					Title style
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
			
			<div class="description third last">
			
				<label for="<?php echo $this->get_field_id('enter_anim') ?>">Viewport enter animation</label><br/>	
				<?php
				echo aq_field_select('enter_anim', $block_id, $this->animations_array(), $enter_anim ); 
				?>
				
			
			</div>
			
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
				
				<a href="#" rel="item" class="aq-sortable-add-new button">Add New Testimonial</a>
				
				
			</div>
			
			<div class="description third">
				<label for="<?php echo $this->get_field_id('image_style') ?>">Images style</label><br/>
					
				<?php 
				$img_styles = array(
					'square'=> 'Square',
					'round' => 'Round',
					);
				echo aq_field_select('image_style', $block_id, $img_styles, $image_style) ?>
				
			</div>
			
			<div class="description third">
			
				<label for="<?php echo $this->get_field_id('slider_pagin') ?>">Slider pagination</label><br />
				<?php echo aq_field_checkbox('slider_pagin', $block_id, $slider_pagin); ?>
				
				<div class="clearfix clear"></div>
						
				<label for="<?php echo $this->get_field_id('slider_navig') ?>">Slider navigation</label><br />
				<?php echo aq_field_checkbox('slider_navig', $block_id, $slider_navig); ?>
				<div class="clearfix clear"></div>
					
				<label for="<?php echo $this->get_field_id('slider_timing') ?>">Slider timing</label><br />
				<?php echo aq_field_input('slider_timing', $block_id, $slider_timing, $size = 'min');	?>
			
				<p class="description">Timing for auto sliding. Use the miliseconds ( 1second = 1000 miliseconds; example: 100 for 1/10th of second ).If left empty the slider won't auto slide.</p>
			
			</div>
			
			<div class="description third last">
			
			
				<label for="<?php echo $this->get_field_id('items_desktop') ?>">Items in desktop width</label><br />
				<?php echo aq_field_input('items_desktop', $block_id, $items_desktop, $size="min"); ?>
			
				<div class="clearfix"></div>
			
				<label for="<?php echo $this->get_field_id('items_desktop_small') ?>">Items in desktop smaller</label><br />
				<?php echo aq_field_input('items_desktop_small', $block_id, $items_desktop_small, $size="min"); ?>
				
				<div class="clearfix"></div>
			
				<label for="<?php echo $this->get_field_id('items_tablet') ?>">Items in tablet view</label><br />
				<?php echo aq_field_input('items_tablet', $block_id, $items_tablet, $size="min"); ?>
				
				<div class="clearfix"></div>
			
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
							Testimonial Title<br/>
							<input type="text" id="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('items') ?>[<?php echo $count ?>][title]" value="<?php echo $item['title'] ?>" />
						</label>
					
						<div class="clearfix"></div>
					
						<label for="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-content">
							Testimonial Content<br/>
							<textarea id="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-content" class="textarea-full" name="<?php echo $this->get_field_name('items') ?>[<?php echo $count ?>][content]" rows="5"><?php echo $item['content'] ?></textarea>
						</label>
					
						<div class="clearfix"></div>
						
						<label for="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-name">
							Name<br/>
							<input type="text" id="<?php echo $this->get_field_id('items') ?>-<?php echo $count ?>-name" class="input-full" name="<?php echo $this->get_field_name('items') ?>[<?php echo $count ?>][name]" value="<?php echo $item['name'] ?>" />
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
						
			$output = '';
			
			$output .= '<div id="tesimonial-slides-'. $block_id .'" class="aq_block_items content-block '.$css_classes.'">';
				
				$output .= '<div class="header-holder '. $title_style. '">';
				
				$output .= $main_title ? '<h2 class="block-title '.$title_style .'">'.$main_title.'</h2>' : '';
				
				$output .= '</div>';
				
				$output .= '<input type="hidden" class="slides-config" data-navigation="'. $slider_navig .'" data-pagination="'. $slider_pagin .'" data-auto="'.$slider_timing.'" data-desktop="'.$items_desktop.'" data-desktop-small="'.$items_desktop_small.'" data-tablet="'.$items_tablet.'" data-mobile="'.$items_mobile.'"  />';
				
				$output .= '<div class="testimonials'. (count($items) > 1 ? ' contentslides' : '') .'">';
				
				$i = 1;
				$anim = ($enter_anim != 'none') ? ' to-anim' : '';
				
				foreach( $items as $item ){
					
					$img = $item['image'];
					
					$output .= '<div class="testimonial-item column item '.$anim.'" data-i="'.$i.'">';
					if( $item['image'] ) { 
					$output .= '<div class="image '. $image_style .'">';
						
						$attr = array(
							'class' => 'attachment-image',
							'title'	=> $item['title'] ? $item['title'] : '',
							'alt'	=> $item['title'] ? $item['title'] : ''
						);
						
						$output .= wp_get_attachment_image( $img, 'thumbnail', false,  $attr ); 
					
						$output .= '<div class="arrow-left"></div>';
						$output .= '</div>';
					};
					$output .= '<div class="content" '. ( !$img ? 'style="width: 100%;"' : '' ).'>';
					$output .= $item['title'] ? '<h4>' .$item['title'] .'</h4>' : '';	
					$output .= wpautop(do_shortcode(htmlspecialchars_decode($item['content']))) ;
					$output .= $item['name'] ? '<h6>' .$item['name'] .'</h6>' : '';	
					$output .= '</div>';
					$output .= '</div>';
					$i++;
				}
				
				$output .= '</div>';
				
			
			$output .= '</div>';
			
			echo $output;
			
			if( $enter_anim != 'none' ) {
			?>
		
		<script>
		(function( $ ){
			$.fn.anim_waypoints_img_tests = function(blockId, enter_anim) {
				
				var thisBlock = $('#tesimonial-slides-'+ blockId );
				if ( !window.isMobile && !window.isIE9 ) {
					var item = thisBlock.find('.testimonial-item');
					
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
			
			$(document).anim_waypoints_img_tests("<?php echo $block_id; ?>"," <?php echo $enter_anim;?>");
		
		});
		</script>
		
		<?php 
		
			}// if enter_anim
		
		} // function block
		
		
		/* AJAX add tab */
		function add_testimonial() {
			$nonce = $_POST['security'];
			if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');
			
			$count = isset($_POST['count']) ? absint($_POST['count']) : false;
			$this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';
			
			//default key/value for the tab
			$item = array(
				'image'		=> '',
				'title'		=> 'New Testimonial',
				'content'	=> '',
				'name'	=> ''
			);
			
			if($count) {
				$this->create_item($item, $count);
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
