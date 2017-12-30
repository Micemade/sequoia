<?php
if(!class_exists('AS_Onepager_logo')) {
	
	class AS_Onepager_logo extends AQ_Block {

		function __construct() {

			$block_options = array (
				'name' => 'Onepager logo',
				'size' => 'span12',
			);
			
			parent::__construct('as_onepager_logo', $block_options);

		}
		
		function form($instance) {
	
			global $post;
			
			// default key/values array
			$defaults = array(
				'img_format'	=> 'medium',
				'attach_id'		=> '',
				'title'			=> '',
				'color'			=> '#333333',
				'bck_color'		=> '#fff',
				'logo_align'	=> 'center',
				'logo_width'	=> '100',
				'logo_padding'	=> '15',
				'link'			=> '',
				'target'		=> '',
				'css_classes'	=> '',
			);

			// set default values (if not yet defined)
			$instance = wp_parse_args($instance, $defaults);

			// import each array key as variable with defined values
			extract($instance);
			

			
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
				
				<label for="<?php echo $this->get_field_id('logo_width') ?>" class="half">Logo width</label><br/>
				<?php echo aq_field_input('logo_width', $block_id, $logo_width, $size = 'min' ) ?>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('logo_padding') ?>" class="half">Logo padding</label><br />
				<?php echo aq_field_input('logo_padding', $block_id, $logo_padding, $size = 'min');	?>
			
			
			<div class="clearfix"></div>

			
			</div>	
			
			<hr>
			
			<div class="clearfix"></div>
			
			<div class="description half">
				
				<label for="<?php echo $this->get_field_id('title') ?>">Title <small>If no logo (defaults to site title)</small></label>	
				<?php echo aq_field_input('title', $block_id, $title) ?>
				
				<div class="clearfix"></div>
			
				<label for="<?php echo $this->get_field_id('logo_align') ?>">Logo / title position</label><br/>	
				<?php
				$logo_aligns = array(
					'center'	=> 'Center',
					'left'		=> 'Left',
					'right'		=> 'Right'
					);
				echo aq_field_select('logo_align', $block_id, $logo_aligns, $logo_align); 
				?>
			
			</div>	
			
			<div class="description half last">

				
				<label for="<?php echo $this->get_field_id('color') ?>">Title color
				</label><br />
				<?php echo aq_field_color_picker('color', $this->block_id, $color, $defaults['color']) ?>
			
				
				<label for="<?php echo $this->get_field_id('bck_color') ?>">Background color
				</label><br />
				<?php echo aq_field_color_picker('bck_color', $this->block_id, $bck_color, $defaults['bck_color']) ?>
				
				
				<label for="<?php echo $this->get_field_id('link') ?>">Link <small>preferably to home page (reload)</small></label>	
				<?php echo aq_field_input('link', $block_id, $link) ?>
				
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
			
			?>
			<style type="text/css">
			<?php 
			
			echo $bck_color ? '#'.$block_id.' { background-color: '. $bck_color .' }' : '';
			
			echo '#'.$block_id.', #'.$block_id.' h1, #'.$block_id.' h1 .entry-image { ';
			echo $color ? 'color: '. $color. ';' : null;
			echo $logo_align ? 'text-align: '. $logo_align. ';' : null;
			if( $logo_align == 'center'){
				echo 'float: none; margin: 0 auto;';
			}else{
				echo 'float:'.$logo_align.';';
			}
			echo '}';
			
			if( $logo_width && $logo_padding ) {
				
				echo '#'.$block_id.' h1 .entry-image { ';
				
				echo $logo_width ?		'width:'.$logo_width.'px;' : '';
				echo $logo_padding ?	'padding:'.$logo_padding.'px;' : '';
			
				echo '}';
			
			}
			
			?>
			</style>
			

			
			<div id="<?php echo $block_id; ?>" class="onepager-logo-block inner-wrapper item<?php echo $css_classes ? ' '.$css_classes : '';?>">
				
				<h1>
					
					<?php
					
					echo $link ? '<a href="'. esc_url($link) .'">' : null;
					
					if( $attach_id ) {
					
						$img_width = $img_height = '';
						echo as_get_unattached_image( $attach_id, $img_format, $img_width, $img_height );
						
					}elseif( !$attach_id && $title ) {
					
						echo esc_html( $title );
						
					}else{
						bloginfo( 'name' );
					}
					
					echo $link ? '</a>' : null;
					?>
				
				</h1>

				
				
				<div class="clearfix"></div>

			</div>
						
			<div class="clearfix"></div>
			
		<?php 

		} // function block
	
	} // class
	
}// if !class_exists
?>