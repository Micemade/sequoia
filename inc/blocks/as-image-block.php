<?php
if(!class_exists('AS_Image_Block')) {
	
	class AS_Image_Block extends AQ_Block {

		function __construct() {

			$block_options = array (
				'name' => 'Image',
				'size' => 'span6',
			);
			
			parent::__construct('as_image_block', $block_options);

		}
		
		function animations_array() {
			
			include ( get_template_directory() .'/inc/functions/animations-icons-arrays.php' );
			return $block_enter_anim_arr;

		}
		
		function form($instance) {
	
			global $post;
			
			// default key/values array
			$defaults = array(
				'img_format'		=> 'medium',
				'attach_id'			=> '',
				'caption_title' 	=> '',
				'caption_title_size'=> 'normal',
				'text'				=> '',
				'link'				=> '',
				'text_color'		=> '#333333',
				'text_align'		=> 'center',
				'img_width'			=> '',
				'img_height'		=> '',
				'enter_anim'		=> 'fadeIn',
				'anim_delay'		=> 100,
				'target'			=> '',
				'css_classes'		=> '',
			);

			// set default values (if not yet defined)
			$instance = wp_parse_args($instance, $defaults);

			// import each array key as variable with defined values
			extract($instance);
			
			if ( $enter_anim != 'none' && !wp_style_is( 'animate', 'enqueued' )) {
				
				wp_register_style( 'animate', get_template_directory_uri() . '/css/animate.css');
				wp_enqueue_style( 'animate' );
				
			}
			?>
			
			<div class="description half">
				
				<div class="screenshot member-image">
				
					<input type="hidden" class="placeholder" value="<?php echo PLACEHOLDER_IMAGE; ?>" />
					
					<a href="#" class="remove-media"><img src="<?php echo get_template_directory_uri(); ?>/admin/images/icon-delete.png" /></a>
					
					<?php
					if( $attach_id ) {					
						$imgurl = wp_get_attachment_image_src( $attach_id, 'thumbnail' );
						echo '<img src="'. $imgurl[0] .'" class="att-image" />';
					}else{
						echo '<img src="'. PLACEHOLDER_IMAGE .'" class="att-image" />';
					}
					?>
					
				</div>
				<br />
				<label for="<?php echo $this->get_field_id('attach_id') ?>">
					<?php echo as_field_upload('attach_id', $block_id, $attach_id, 'thumbnail'); ?>
				</label>
			</div>	
			

			<div class="description half last">
			
				<hr>
				
				<label for="<?php echo $this->get_field_id('enter_anim') ?>">Viewport enter animation</label><br/>	
				<?php
				echo aq_field_select('enter_anim', $block_id, $this->animations_array(), $enter_anim ); 
				?>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('anim_delay') ?>">Animation delay (milisec.)</label><br/>	
				<?php echo aq_field_input('anim_delay', $block_id, $anim_delay, $type="number") ;?>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('img_format') ?>">Image format</label><br/>	
				<?php
				$img_formats = array(
					'thumbnail'		=> 'Thumbnail',
					'medium'		=> 'Medium',
					'as-portrait'	=> 'Portrait',
					'as-landscape'	=> 'Landscape',
					'large'			=> 'Large'
					);
				echo aq_field_select('img_format', $block_id, $img_formats, $img_format); 
				?>
				
				<hr>
				
				<label for="<?php echo $this->get_field_id('img_width') ?>">Image width</label><br/>
				<?php echo aq_field_input('img_width', $block_id, $img_width ) ?>
				
				<div class="clearfix"></div>
					
				<label for="<?php echo $this->get_field_id('img_height') ?>">Image height</label><br/>
				<?php echo aq_field_input('img_height', $block_id, $img_height ) ?>

				
				<p class="description">If left empty, the image format settings will be used.</p>
			
			</div>	
			
			<hr>
			
			<div class="clearfix"></div>
			
			<div class="description half">
				
				<label for="<?php echo $this->get_field_id('caption_title') ?>">Caption title</label><br/>	
				<?php echo aq_field_input('caption_title', $block_id, $caption_title) ?>
			
				<label for="<?php echo $this->get_field_id('title_size') ?>">Caption title size</label><br/>
				<?php
				$caption_title_sizes = array(
					'normal'		=> 'Normal',
					'medium'		=> 'Medium',
					'large'			=> 'Large',
					'extra_large'	=> 'Extra large',
					);
				echo aq_field_select('caption_title_size', $block_id, $caption_title_sizes, $caption_title_size); 
				?>

		
			
			</div>	
			
			<div class="description half last">
			
				<label for="<?php echo $this->get_field_id('text') ?>">Text</label><br/>
				<?php echo aq_field_textarea('text', $block_id, $text, $size = 'full') ?>
				
				
				
				
				<label for="<?php echo $this->get_field_id('text_color') ?>">Text and title color
				</label><br />
				<?php echo aq_field_color_picker('text_color', $this->block_id, $text_color, $defaults['text_color']) ?>
				
				<label for="<?php echo $this->get_field_id('text_align') ?>">Text float</label><br/>	
				<?php
				$text_aligns = array(
					'center'	=> 'Center',
					'left'		=> 'Left',
					'right'		=> 'Right'
					);
				echo aq_field_select('text_align', $block_id, $text_aligns, $text_align); 
				?>
				
				<label for="<?php echo $this->get_field_id('link') ?>">Link</label><br/>
				<?php echo aq_field_textarea('link', $block_id, $link, $size = 'full') ?>
				
				<div class="clearfix"></div>			
				
				<label for="<?php echo $this->get_field_id('target') ?>">Open in new tab/window</label><br />
				<?php echo aq_field_checkbox('target', $block_id, $target); ?>
					
			
			</div>
			
			<hr>
		
			<div class="description">	
				<label for="<?php echo $this->get_field_id('css_classes') ?>">Additional CSS classes</label>
				<?php echo aq_field_input('css_classes', $block_id, $css_classes, $size = 'full') ?>
			
				<p class="description small">Add custom css classes, defined in child theme css file, or custom css (in Theme options). Multiple classes can be added, separated by space.</p>
			</div>	
			
			<div class="clearfix"></div>
			
			<?php
			} // function form
					
			function block($instance) {

				// import each array key as variable with defined values
				extract($instance);
				
				if ( $enter_anim && !wp_style_is( 'animate', 'enqueued' )) {
				
					wp_register_style( 'animate', get_template_directory_uri() . '/css/animate.css');
					wp_enqueue_style( 'animate' );
					
				}
				?>
				
				<style scoped>		
				<?php 
				// Title and text color and align
				echo '#image-block-'.$block_id.'{';
				echo $text_color ? 'color: '. $text_color. ';' : null;
				echo $text_align ? 'text-align: '. $text_align. ';' : null;
				echo '}';
				if( !$caption_title && !$text ) {
					echo '#image-block-'.$block_id.' .text-holder { position: absolute; top: 0;right: 0;bottom: 0;left: 0;}';
					echo '#image-block-'.$block_id.' .button { position: absolute; top: 0; left:50%; margin-left: -28px; }';
				}
				?>
				</style>

				
				<div id="image-block-<?php echo $block_id; ?>" class="image-block inner-wrapper item<?php echo ($enter_anim != 'none') ? ' to-anim' :''; echo $css_classes ? ' '.$css_classes : ''; ?>">
				
					
					<div class="item-images">
					<div class="item-img">
					
						<div class="front">
					
							<?php
							echo as_get_unattached_image( $attach_id, $img_format, $img_width, $img_height, $caption_title  );
							?>
							
						</div>
						
						
						<div class="back">
						
							<div class="item-overlay"></div>
							
							<?php
							$padd_add	= $text ? 20 : 0;
							$trgt		= $target ? ' target="_blank"' : '';
							
							echo '<div class="text-holder '. $text_align .'">';
							
							if( $caption_title ) {
								echo '<style scoped>';
								echo '#image-block-'.$block_id.' h3, #image-block-'. $block_id. ' .text { ';
								echo $text_color ? 'color: '. $text_color. ';' : null;
								echo $text_align ? 'text-align: '. $text_align. ';' : null;
								echo '}';
								echo '</style>';
							}
							
							echo $caption_title ? '<h3 class="'. $caption_title_size .'">'. esc_html( $caption_title ).'</h3>' :  null; 
											
							echo $text ? '<div class="text">'. esc_html( $text ).'</div>' :  null; 
							
							$title_attr = $caption_title ? 'title="'.esc_attr($caption_title ).'"' : '';
							
							if( $attach_id ) {
							
								$big_img_data = wp_get_attachment_image_src( $attach_id, 'full', false );
								$big_img_url = $big_img_data[0];
							
								if( $text ) {
								
									echo '<a href="'.$big_img_url.'" class="button tiny magnificpopup mfp-image" '. $title_attr.''.$trgt.' ><div class="fs" aria-hidden="true" data-icon="&#xe022;"></div></a>';
									
								}
								if( $link ) {
								
									echo '<a href="'.esc_attr($link).'" class="button tiny" '. $title_attr.''.$trgt.'><div class="fs" aria-hidden="true" data-icon="&#xe065;"></div></a>';
									
								}
								
							}
							
							echo '</div>';
							?>
						</div>
					
					</div><!-- item-img-->
					</div><!-- item-images-->
					
					<div class="clearfix"></div>

				</div>
							
				<div class="clearfix"></div>
				
				<?php $delay = $anim_delay ? $anim_delay : 100 ?>
			
				<script>
				jQuery(document).ready( function($) {
					
					var thisBlock = $('#image-block-<?php echo $block_id;?>');
					
					if ( !window.isMobile && !window.isIE9 ) {

						thisBlock.waypoint(
						
							function(direction) {
								
								if( direction === "up" ) {	
									
									thisBlock.removeClass('animated <?php echo $enter_anim;?>').addClass('to-anim');
									
								}else if( direction === "down" ) {
									
									setTimeout(function(){
									   thisBlock.addClass('animated <?php echo $enter_anim;?>').removeClass('to-anim');
									}, <?php echo $delay; ?>);
								}
							}, 
							{ offset: "98%" }	
						
						);

					}else{
				
						thisBlock.each( function() {
							
							$(this).removeClass('to-anim');
						
						});
						
					}
				
				});
				</script>
				
			<?php 

			} // function block
	
	} // class
	
}// if !class_exists
?>