<?php

class AS_Row_Block extends AQ_Block {
	
	/* PHP5 constructor */
	function __construct() {
		
		$block_options = array(
			'name' => 'ROW block',
			'size' => 'span12',
			'resizable' => 0
		);
		
		//create the widget
		parent::__construct('as_row_block', $block_options);
		
	}

	function form($instance) {
		
		$defaults = array(

			'text_color'		=> '',
			'links_color'		=> '',
			'titles_color'		=> '',
			'image'				=> '',
			'overlay'			=> '',
			'overlay_opacity'	=> '70',
			'paralax'			=> false,
			'back_repeat'		=> '',
			'back_ratio'		=> '0.1',
			'back_size'			=> '',
			'fixed'				=> '',
			'padding_top'		=> '0',
			'padding_bottom'	=> '0',
			'full_width'		=> false,
			'eq_heights'		=> false,
			'no_side_gutter'	=> false,
			'no_bottom_gutter'	=> false,
			'videoURL'			=> '',
			'html5video'		=> '',
			'autoPlay'			=> true,
			'mute'				=> true,
			//'showControls'		=> true,
			'optimizeDisplay'	=> true,
			'loop'				=> true,
			'quality'			=> 'default',
			'ratio'				=> '16/9',
			'volume'			=> '50'
			
		);
				
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);	
		?>
		
		<p class="empty-row">
		<?php echo __('ROW BLOCK', 'sequoia'); ?>
		<a class="row-edit" title="Edit Settings" href="#"><?php echo __('Row settings','sequoia')?></a>
		</p>
		
		<div class="row-special-settings">
		
			<div class="description row_color_picker fourth">
				
				
				<label for="<?php echo $this->get_field_id('titles_color') ?>">
				<?php echo __('Titles color','sequoia'); ?>
				</label><br />
				<?php echo aq_field_color_picker('titles_color', $this->block_id, $titles_color, $defaults['titles_color']) ?>
				
				<label for="<?php echo $this->get_field_id('text_color') ?>">
				<?php echo __('Text color','sequoia'); ?>
				</label><br />
				<?php echo aq_field_color_picker('text_color', $this->block_id, $text_color, $defaults['text_color']) ?>
				
				<label for="<?php echo $this->get_field_id('links_color') ?>">
				<?php echo __('Links color','sequoia'); ?>
				</label><br />
				<?php echo aq_field_color_picker('links_color', $this->block_id, $links_color, $defaults['links_color']) ?>
	
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('overlay') ?>">
				<?php echo __('Overlay color','sequoia'); ?>
				</label><br />
				<?php echo aq_field_color_picker('overlay', $this->block_id, $instance['overlay']) ?>
				
				<div class="clearfix"></div>

				<label for="<?php echo $this->get_field_id('overlay_opacity') ?>">Overlay opacity</label><br />
				<?php echo aq_field_input('overlay_opacity', $this->block_id, $overlay_opacity, $size="min"); ?> %
			
				<p class="description">NOTE: Text color will apply only to unspecified text color in blocks. Use it to adjust text color of title (and other) depending on applied background. Titles color apply only to block titles, not inner content titles.</p>
			
			
			</div>

			<div class="description fourth first">
				
				<label for="<?php echo $this->get_field_id('image') ?>">Row background image</label>
				
				<div class="screenshot member-image">
				
					<input type="hidden" class="placeholder" value="<?php echo PLACEHOLDER_IMAGE; ?>" />
					
					<a href="#" class="remove-media"><img src="<?php echo get_template_directory_uri(); ?>/admin/images/icon-delete.png" /></a>
					
					<?php
					if( $image ) {					
						$imgurl = wp_get_attachment_image_src( $image, 'thumbnail' );
						echo '<img src="'. $imgurl[0] .'" class="att-image" />';
					}else{
						echo '<img src="'. PLACEHOLDER_IMAGE .'" class="att-image" />';
					}
					?>
					
				</div>
				<br />
	
				<?php echo as_field_upload('image', $this->block_id, $instance['image'], 'thumbnail'); ?>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('back_repeat') ?>">Background repeat:</label><br/>
				<?php
				$back_repeat_arr = array(
					''			=> '',
					'repeat'	=> 'Repeat',
					'no-repeat'	=> 'No repeat',
					'repeat-x'	=> 'Repeat X',
					'repeat-y'	=> 'Repeat Y'
				);
				echo aq_field_select('back_repeat', $this->block_id, $back_repeat_arr, $defaults['back_repeat'] ) ; 
				?>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('back_size') ?>">Background size:</label>
				<?php
				$back_size_arr = array(
					''			=> '',
					'50%'		=> '50%',
					'100% 100%'	=> '100%',
					'cover'		=> 'Cover',
					'contain'	=> 'Contain'
				);
				echo aq_field_select('back_size', $this->block_id, $back_size_arr, $defaults['back_size']); 
				?>
			
			</div>
			
			
			<div class="description fourth">
				
				<label for="<?php echo $this->get_field_id('padding_top') ?>">Padding top</label><br />
				<?php echo aq_field_input('padding_top', $this->block_id, $padding_top, $size="min"); ?>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('padding_bottom') ?>">Padding bottom</label><br />
				<?php echo aq_field_input('padding_bottom', $this->block_id, $padding_bottom, $size="min"); ?>
			
				<div class="clear clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('full_width') ?>"><?php echo __('Full width content in row ?','sequoia');?></label><br />
				<?php echo aq_field_checkbox('full_width', $this->block_id,  $defaults['full_width']) ;?>
			
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('eq_heights') ?>"><?php echo __('Equalize inner blocks heights','sequoia');?></label><br />
				<?php echo aq_field_checkbox('eq_heights', $this->block_id,  $defaults['eq_heights']) ;?>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('no_side_gutter') ?>"><?php echo __('Remove side gutter','sequoia');?></label><br />
				<?php echo aq_field_checkbox('no_side_gutter', $this->block_id,  $defaults['no_side_gutter']) ;?>
			
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('no_bottom_gutter') ?>"><?php echo __('Remove bottom gutter','sequoia');?></label><br />
				<?php echo aq_field_checkbox('no_bottom_gutter', $this->block_id,  $defaults['no_bottom_gutter']) ;?>
			
				<p class="description">Gutters are spaces created by theme layout grid. Check upper checkboxes to remove those spaces.</p>
			
			</div>	
			
			<div class="description fourth">
				
				<label for="<?php echo $this->get_field_id('paralax') ?>">Parallax background ?</label><br />
				<?php 
				echo aq_field_checkbox('paralax', $this->block_id,  $defaults['paralax']) ;
				?>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('back_ratio') ?>">Parallax background ratio</label><br />
				<?php echo aq_field_input('back_ratio', $this->block_id, $defaults['back_ratio'], $size="min"); ?>
				<br />
				<p class="description">Enter the value between 0 and 1. If empty, default of 0.1 will be aplied.</p>
				
				<label for="<?php echo $this->get_field_id('fixed') ?>">Fixed background ?</label><br />
				<?php 
				echo aq_field_checkbox('fixed', $this->block_id,  $defaults['fixed']) ;
				?>
				<p class="description">This setting will override parallax setting.</p>
				
			</div>
			
			
			<hr>
			
			<h4>Youtube video background</h4>
			<p class="description" style="margin: -15px 0 20px;border-top: 1px dotted;">Tip: use row background image as video poster image until video loads.</p>
			
			<div class="description third">
			
				<label for="<?php echo $this->get_field_id('videoURL') ?>">YouTube video ID</label><br />
				<?php echo aq_field_input('videoURL', $this->block_id, $defaults['videoURL'] ); ?>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('autoPlay') ?>">Auto play video?</label><br />
				<?php 
				echo aq_field_checkbox('autoPlay', $this->block_id,  $defaults['autoPlay']) ;
				?>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('mute') ?>">Mute video?</label><br />
				<?php 
				echo aq_field_checkbox('mute', $this->block_id,  $defaults['mute']) ;
				?>
			
			</div>
			
			<div class="description third">			
				<!-- 
				<label for="<?php //echo $this->get_field_id('showControls') ?>">Show controls?</label><br />
				<?php 
				//echo aq_field_checkbox('showControls', $this->block_id,  $defaults['showControls']) ;
				?>
				-->
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('optimizeDisplay') ?>">Optimize display?</label><br />
				<?php 
				echo aq_field_checkbox('optimizeDisplay', $this->block_id,  $defaults['optimizeDisplay']) ;
				?>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('loop') ?>">Loop video?</label><br />
				<?php 
				echo aq_field_checkbox('loop', $this->block_id,  $defaults['loop']) ;
				?>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('volume') ?>">Volume</label><br />
				<?php echo aq_field_input('volume', $this->block_id, $defaults['volume'], $size="min"); ?> %
				
				<p class="description">Enter the value between 0 and 100</p>
				
				<div class="clearfix"></div>
			
			</div>
			
			
			<div class="description third">	
				
				<label for="<?php echo $this->get_field_id('quality') ?>">Video quality:</label>
				<?php
				$quality_arr = array(
					'default'	=> 'Default',
					'small'		=> 'Small',
					'medium'	=> 'Medium',
					'large'		=> 'Large',
					'hd720'		=> 'HD720',
					'hd1080'	=> 'HD1080',
					'highres'	=> 'High resolution'
				);
				echo aq_field_select('quality', $this->block_id, $quality_arr, $defaults['quality']); 
				?>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('ratio') ?>">Video image ratio:</label>
				<?php
				$ratio_arr = array(
					'16/9'		=> '16/9',
					'4/3'		=> '4/3',
				);
				echo aq_field_select('ratio', $this->block_id, $ratio_arr, $defaults['ratio']); 
				?>

			</div>
		
		<hr>
		
		<div class="description">
		
			<h4><strong>Video URL (HTML5 VIDEO)</strong></h4>
			
			<label for="<?php echo $this->get_field_id('html5video') ?>">URL to video</label><br />
			<?php echo aq_field_input( 'html5video', $this->block_id, $html5video ); ?>
			
			<strong>Self hosted video URL or URL to remote video files. </strong><br>
			Enter full path to video file name and exclude extension - the mp4, webm and ogg extension will be added automatically.<br>
			All required video file types OGV, WEBB and OGG files should be available (locally, self-hosted, or remote) for browser compatibility.
	
		</div>
		
		<hr>
		
		<p class="description">ADVICE: Use video background with caution - recommended usage of single video per page because of performance and bandwidth</p>
		
		<div class="clearfix"></div>
		</div>

		
	<?php		
		
		echo '<ul class="blocks row-blocks cf"></ul>';
		

	}
	
	function form_callback($instance = array()) {
	
		
		$instance = is_array($instance) ? wp_parse_args($instance, $this->block_options) : $this->block_options;
		
		//insert the dynamic block_id & block_saving_id into the array
		$this->block_id = 'aq_block_' . $instance['number'];
		$instance['block_saving_id'] = 'aq_blocks[aq_block_'. $instance['number'] .']';
		
		extract($instance);
		
		$row_order = $order;
		
		//row block header
		if(isset($template_id)) {
		
			$defaults = array(
				'text_color'		=> '',
				'links_color'		=> '',
				'titles_color'		=> '',
				'image'				=> '',
				'overlay'			=> '',
				'overlay_opacity'	=> '70',
				'paralax'			=> true,
				'back_repeat'		=> '',
				'back_ratio'		=> '0.1',
				'back_size'			=> '',
				'fixed'				=> '',
				'padding_top'		=> '0',
				'padding_bottom'	=> '0',
				'full_width'		=> false,
				'eq_heights'		=> false,
				'no_side_gutter'	=> false,
				'no_bottom_gutter'	=> false,
				'videoURL'			=> '',
				'html5video'		=> '',
				'autoPlay'			=> true,
				'mute'				=> true,
				//'showControls'		=> true,
				'optimizeDisplay'	=> true,
				'loop'				=> true,
				'quality'			=> 'default',
				'ratio'				=> '16/9',
				'volume'			=> '50',
			);
		
			$instance = wp_parse_args($instance, $defaults);
			extract($instance);	
			
			
			echo '<li id="template-block-'.$number.'" class="block block-as_row_block '.$size.'">',
					'<div class="block-settings-row cf" id="block-settings-'.$number.'">',
						'<p class="empty-row">',
							__('ROW BLOCK', 'sequoia'),
							'<span class="block-id-menu" id="">ID: aq-block-'. $number .'</span>',
							'<a class="row-edit" title="Edit Settings" href="#">'. __('Row settings','sequoia').'</a>',
						'</p>';	
			?>
			
			
			<div class="row-special-settings">
			
				<div class="description row_color_picker fourth">		
					
					
					<label for="<?php echo $this->get_field_id('titles_color') ?>">
					<?php echo __('Titles color','sequoia'); ?>
					</label><br />
					<?php echo aq_field_color_picker('titles_color', $this->block_id, $instance['titles_color'], $defaults['titles_color']) ?>
					
					
					<label for="<?php echo $this->get_field_id('text_color') ?>">
					<?php echo __('Text color','sequoia'); ?>
					</label><br />
					<?php echo aq_field_color_picker('text_color', $this->block_id, $instance['text_color'], $defaults['text_color']) ?>
					
					<label for="<?php echo $this->get_field_id('links_color') ?>">
					<?php echo __('Links color','sequoia'); ?>
					</label><br />
					<?php echo aq_field_color_picker('links_color', $this->block_id, $instance['links_color'], $defaults['links_color']) ?>
					
					<label for="<?php echo $this->get_field_id('overlay') ?>">
					<?php echo __('Overlay color','sequoia'); ?>
					</label><br />
					<?php echo aq_field_color_picker('overlay', $this->block_id, $instance['overlay'] ) ?>
					
					<label for="<?php echo $this->get_field_id('overlay_opacity') ?>">Overlay opacity</label><br />
					<?php echo aq_field_input('overlay_opacity', $this->block_id, $overlay_opacity, $size="min"); ?> %
					
					<p class="description">NOTE: Text color will apply only to unspecified text color in blocks. Use it to adjust text color of title (and other) depending on applied background.</p>
				
				</div>
				
				
				<div class="description fourth first">
					
					<label for="<?php echo $this->get_field_id('image') ?>">Row background image</label>
					
					<div class="screenshot member-image">
					
						<input type="hidden" class="placeholder" value="<?php echo PLACEHOLDER_IMAGE; ?>" />
						
						<a href="#" class="remove-media"><img src="<?php echo get_template_directory_uri(); ?>/admin/images/icon-delete.png" /></a>
						
						<?php
						if( $image ) {					
							$imgurl = wp_get_attachment_image_src( $image, 'thumbnail' );
							echo '<img src="'. $imgurl[0] .'" class="att-image" />';
						}else{
							echo '<img src="'. PLACEHOLDER_IMAGE .'" class="att-image" />';
						}
						?>
						
					</div>
					
					<div class="clearfix"></div>
			
					<?php echo as_field_upload('image', $this->block_id, $instance['image'], 'thumbnail'); ?>
					
					<div class="clearfix"></div>
					
					<label for="<?php echo $this->get_field_id('back_repeat') ?>">Background repeat:</label>
					<?php
					$back_repeat_arr = array(
						'' => '',
						'repeat' => 'Repeat',
						'no-repeat' => 'No repeat',
						'repeat-x' => 'Repeat X',
						'repeat-y' => 'Repeat Y'
					);
					echo aq_field_select('back_repeat', $this->block_id, $back_repeat_arr, $back_repeat); 
					?>
					
					<div class="clearfix"></div>
					
					<label for="<?php echo $this->get_field_id('back_size') ?>">Background size:</label>
					<?php
					$back_size_arr = array(
						'' => '',
						'50% auto'=> '50%',
						'100% auto'=> '100%',
						'cover'=> 'Cover',
						'contain'=> 'Contain'
					);
					echo aq_field_select('back_size', $this->block_id, $back_size_arr, $back_size ); 
					?>
		
				</div>	
				
				
				
				<div class="description fourth">
					
					<label for="<?php echo $this->get_field_id('padding_top') ?>">Padding top</label><br />
					<?php echo aq_field_input('padding_top', $this->block_id, $padding_top, $size="min"); ?>
					
					<div class="clearfix"></div>
					
					<label for="<?php echo $this->get_field_id('padding_bottom') ?>">Padding bottom</label><br />
					<?php echo aq_field_input('padding_bottom', $this->block_id, $padding_bottom, $size="min"); ?>
				
					<div class="clearfix"></div>
					
					<label for="<?php echo $this->get_field_id('full_width') ?>"><?php echo __('Full width content in row ?','sequoia');?></label><br />
					<?php echo aq_field_checkbox('full_width', $this->block_id,  $full_width) ;?>
					
					<div class="clearfix"></div>
					
					<label for="<?php echo $this->get_field_id('eq_heights') ?>"><?php echo __('Equalize inner blocks heights ?','sequoia');?></label><br />
					<?php echo aq_field_checkbox('eq_heights', $this->block_id,  $eq_heights) ; ?>
				
					<div class="clearfix"></div>
				
					<label for="<?php echo $this->get_field_id('no_side_gutter') ?>"><?php echo __('Remove side gutter','sequoia');?></label><br />
					<?php echo aq_field_checkbox('no_side_gutter', $this->block_id,  $no_side_gutter) ;?>
				
					<div class="clearfix"></div>
					
					<label for="<?php echo $this->get_field_id('no_bottom_gutter') ?>"><?php echo __('Remove bottom gutter','sequoia');?></label><br />
					<?php echo aq_field_checkbox('no_bottom_gutter', $this->block_id,  $no_bottom_gutter) ;?>
				
					<p class="description">Gutters are spaces created by theme layout grid. Check upper checkboxes to remove those spaces.</p>
				
				</div>	
				
				
				<div class="description fourth">
					<label for="<?php echo $this->get_field_id('paralax') ?>">Parallax background ?</label><br />
					<?php 
					echo aq_field_checkbox('paralax', $this->block_id,  $paralax) ;
					?>
					
					<div class="clear clearfix"></div>
					
					<label for="<?php echo $this->get_field_id('back_ratio') ?>">Parallax background ratio</label><br />
					<?php echo aq_field_input('back_ratio', $this->block_id, $back_ratio, $size="min"); ?>
					<br />
					<p class="description">Enter the value between 0 and 1. If empty, default of 0.1 will be aplied.</p>
					
					<label for="<?php echo $this->get_field_id('fixed') ?>">Fixed background ?</label><br />
					<?php 
					echo aq_field_checkbox('fixed', $this->block_id,  $fixed) ;
					?>
					<p class="description">This setting will override parallax setting.</p>
					
				</div>	

				
				<hr>
			
				<h4>Youtube video background</h4>
				<p class="description" style="margin: -15px 0 20px; border-top: 1px dotted;">Tip: use row background image as video poster image until video loads.</p>
				
				<div class="description third">
			
					<label for="<?php echo $this->get_field_id('videoURL') ?>">YouTube video ID</label><br />
					<?php echo aq_field_input( 'videoURL', $this->block_id, $videoURL ); ?>
					
					<div class="clearfix"></div>
					
					<label for="<?php echo $this->get_field_id('autoPlay') ?>">Auto play video?</label><br />
					<?php 
					echo aq_field_checkbox( 'autoPlay', $this->block_id,  $autoPlay ) ;
					?>
					
					<div class="clearfix"></div>	
					
					<label for="<?php echo $this->get_field_id('mute') ?>">Mute video?</label><br />
					<?php 
					echo aq_field_checkbox( 'mute', $this->block_id,  $mute ) ;
					?>
				
				</div>
				
				<div class="description third">			
					<!-- 
					<label for="<?php //echo $this->get_field_id('showControls') ?>">Show controls?</label><br />
					<?php 
					//echo aq_field_checkbox('showControls', $this->block_id,  $showControls ) ;
					?>
					-->
					<div class="clearfix"></div>
				
					<label for="<?php echo $this->get_field_id('optimizeDisplay') ?>">Optimize display?</label><br />
					<?php 
					echo aq_field_checkbox('optimizeDisplay', $this->block_id,  $optimizeDisplay ) ;
					?>
					
					<div class="clearfix"></div>
				
					<label for="<?php echo $this->get_field_id('loop') ?>">Loop video?</label><br />
					<?php 
					echo aq_field_checkbox('loop', $this->block_id,  $loop ) ;
					?>
					<div class="clearfix"></div>
				
					<label for="<?php echo $this->get_field_id('volume') ?>">Volume</label><br />
					<?php echo aq_field_input('volume', $this->block_id, $volume, $size="min"); ?> %
					
					<p class="description">Enter the value between 0 and 100</p>
					
				</div>
				
				
			<div class="description third">	
				
				<label for="<?php echo $this->get_field_id('quality') ?>">Video quality:</label>
				<?php
				$quality_arr = array(
					'default'	=> 'Default',
					'small'		=> 'Small',
					'medium'	=> 'Medium',
					'large'		=> 'Large',
					'hd720'		=> 'HD720',
					'hd1080'	=> 'HD1080',
					'highres'	=> 'High resolution'
				);
				echo aq_field_select('quality', $this->block_id, $quality_arr, $quality); 
				?>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('ratio') ?>">Video image ratio:</label>
				<?php
				$ratio_arr = array(
					'16/9'		=> '16/9',
					'4/3'		=> '4/3',
				);
				echo aq_field_select('ratio', $this->block_id, $ratio_arr, $ratio); 
				?>

			
			</div>
		
			<hr>
			
			<div class="description">
			
				<h4><strong>Video URL (HTML5 VIDEO)</strong></h4>
				
				<label for="<?php echo $this->get_field_id('html5video') ?>">URL to video</label><br>
				<?php echo aq_field_input( 'html5video', $this->block_id, $html5video ); ?>
				
				<p class="description">
				<strong>Self hosted video URL or URL to remote video files. </strong><br>
				Enter full path to video file name and exclude extension - the mp4, webm and ogg extension will be added automatically.<br>
				All required video file types OGV, WEBB and OGG files should be available (locally, self-hosted, or remote) for browser compatibility.
				
				</p>
			</div>
			
			<hr>
			
			<p class="description">ADVICE: Use video background with caution - recommended usage of single video per page because of performance and bandwidth</p>
				
			<div class="clearfix"></div>
			</div>


			
			<?php
			echo '<ul class="blocks row-blocks cf">';
			
			//check if row has blocks inside it
			$blocks = aq_get_blocks($template_id);
			
			//outputs the blocks
			if($blocks) {
				foreach($blocks as $key => $child) {
					global $aq_registered_blocks;
					extract($child);
					
					
					//get the block object
					$block = $aq_registered_blocks[$id_base];
					
					if($parent == $row_order) {
						$block->form_callback($child);
					}
				}
			} 
			echo '</ul>';
			?>

			<?php			
			
		} else {
			$this->before_form($instance);
			$this->form($instance);
		}
				
		//form footer
		$this->after_form($instance);
	}
	
	//form footer
	function after_form($instance) {
		extract($instance);
		
		$block_saving_id = 'aq_blocks[aq_block_'.$number.']';
			
			echo '<div class="block-control-actions cf"><a href="#" class="delete">Delete</a></div>';
			echo '<input type="hidden" class="id_base" name="'.$this->get_field_name('id_base').'" value="'.$id_base.'" />';
			echo '<input type="hidden" class="name" name="'.$this->get_field_name('name').'" value="'.$name.'" />';
			echo '<input type="hidden" class="order" name="'.$this->get_field_name('order').'" value="'.$order.'" />';
			echo '<input type="hidden" class="size" name="'.$this->get_field_name('size').'" value="'.$size.'" />';
			echo '<input type="hidden" class="parent" name="'.$this->get_field_name('parent').'" value="'.$parent.'" />';
			echo '<input type="hidden" class="number" name="'.$this->get_field_name('number').'" value="'.$number.'" />';
		echo '</div>',
			'</li>';
	}
	
	function before_block($instance) {
		extract($instance);
		$row_class = $first ? 'aq-first' : '';
		
		$overlay		= $instance['overlay'];
		$text_color		= $instance['text_color'];
		$links_color	= $instance['links_color'];
		$titles_color	= $instance['titles_color'];
		$bck_image		= $instance['image'];
		$bck_repeat 	= $instance['back_repeat'];
		$back_size		= $instance['back_size'];
		$fixed			= isset($instance['fixed']) ? $instance['fixed'] : '';
		$padd_top		= $instance['padding_top'];
		$padd_bott		= $instance['padding_bottom'];
		$no_side_gutt	= $instance['no_side_gutter'];
		$no_bott_gutt	= $instance['no_bottom_gutter'];
		
		$autoplay		= $instance['autoPlay'];
		$loop			= $instance['loop'];
		$mute			= $instance['mute'];
		
				
		$img_url = wp_get_attachment_image_src( $bck_image, 'large' );
		
		
		// if to equalize row inner blocks:
		if( $instance['eq_heights'] ) {
			$eq_heights = 'eq_heights';
		}
		// MAIN DIV
		
		echo '<div id="aq-block-'.$number.'" class="aq-block aq-block-'.$id_base.' '.$size.' '.$row_class.' cf '.$eq_heights.'">';
		
		if(  $bck_image || $bck_repeat || $back_size || isset($padd_top) || isset($padd_bott) || $links_color || $text_color	|| $titles_color || $no_side_gutt || $no_bott_gutt ) {
			echo '<style type="text/css" scoped> ';
			echo '#aq-block-'.$number.' { ';
			echo isset($padd_top)	? 'padding-top: ' . $padd_top .'px;' : '';
			echo isset($padd_bott)	? 'padding-bottom: ' . $padd_bott .'px;' : '';
			echo $text_color		? 'color:' . $text_color .' ;' : '';
			echo '}';
			
			if( $no_side_gutt || $no_bott_gutt ) {
				echo '#aq-block-'.$number.' .aq-block, #aq-block-'.$number.' .item { ';
				echo $no_side_gutt ? 'padding: 0;' : '';
				echo $no_bott_gutt ? 'margin-bottom: 0;' : '';
				echo '}';
				echo '#aq-block-'.$number.' .content-block { margin: 0; }';
			}
			
			
			echo '#aq-block-'.$number.' .row-image { ';
			echo $bck_image			? 'background-image: url(' . $img_url[0] .') !important; ' : '';
			
			echo $fixed				? 'background-attachment:fixed;' : '';
			
			echo $bck_repeat		? 'background-repeat: ' . $bck_repeat .';' : '';
			echo $back_size			? 'background-size: ' . $back_size .';' : '';
			echo '} ';
			
			echo $links_color	? '#aq-block-'.$number.' a, #aq-block-'.$number.' .button { color:'.$links_color.'; }' : null;
			echo $links_color	? '#aq-block-'.$number.' a:hover, #aq-block-'.$number.' .button:hover { color: #ccc; }' : null;
			
			echo '#aq-block-'.$number.' h1.block-title, #aq-block-'.$number.' h2.block-title,';
			echo '#aq-block-'.$number.' h3.block-title, #aq-block-'.$number.' h4.block-title,';
			echo '#aq-block-'.$number.' h5.block-title, #aq-block-'.$number.' h5.block-title,';
			echo '#aq-block-'.$number.' .aq-block-title,';
			echo '#aq-block-'.$number.' .block-subtitle { ';
			echo $text_color ? 'color:' . $text_color .' ;' : '';
			echo $titles_color ? 'color:' . $titles_color .' ;' : '';
			echo '}';
			
			// BACK-COLOR AND TEXT COLOR APPLIES TO IN-BLOCKS ELEMENTS:
			//echo $overlay ? '#aq-block-'.$number.' select option { background-color: '.$overlay.';}' : null;
			
			echo $overlay ? '#aq-block-'.$number.' .block-title-border:before { background-color: '.$overlay.';}' : null;
			
			//echo $text_color ? '#aq-block-'.$number.' .icon, #aq-block-'.$number.' .post-meta-bottom > span .meta-icon { color: '.$text_color.';}' : null;
			echo '</style>';
		}
				
		
		/* 
		// IF TO LOAD PARALAX SCRIPT:
		if( $instance['paralax'] ) {
			
			if ( !wp_script_is( 'parallax', 'enqueued' )) {
				
				wp_register_script( 'parallax', get_template_directory_uri() . '/js/jquery.parallax-1.1.3.js');
				wp_enqueue_script( 'parallax' );
			}
			
			echo '<script>jQuery(document).ready(function(){';
			echo 'if( !window.Mobile ) {';
			echo 'jQuery("#aq-block-'.$number.'").waypoint( function() {';
			echo 'jQuery("#aq-block-'.$number.' .row-image").parallax("50%", ' . $bck_ratio . ', true);';
			echo '}, { offset: "100%" });'; // end waypoint
			echo '}';
			echo '});</script>';// end doc ready
		}
		 */

		
		// IF TO LOAD VIDEO:
		if( $instance['videoURL'] ) {
		
			if( !wp_script_is( 'YTPlayer', 'enqueued' )) {
				wp_register_script( 'YTPlayer', get_template_directory_uri() . '/js/jquery.mb.YTPlayer.min.js');
				wp_enqueue_script( 'YTPlayer' );
			}
			
			echo '<script>';
			echo 'jQuery(function(){';
				echo 'if( !window.isMobile ) {';
				echo 'jQuery("#aq-block-'.$number.' .row-image .yt-video").mb_YTPlayer();';
				echo '}';
			echo '});';
			echo '</script>';
			
			$video_data_property = array(
				'videoURL'			=> '\''. $instance['videoURL'] .'\'',
				'containment'		=> '\'self\'',
				'autoPlay'			=> $autoplay,
				'mute'				=> $mute,
				//'showControls'	=> $instance['showControls'],
				'showControls'		=> 0,
				'optimizeDisplay'	=> $instance['optimizeDisplay'] ,
				'loop'				=> $loop,
				'quality'			=> '\''. $instance['quality'] .'\'',
				'ratio'				=> '\''. $instance['ratio'] .'\'',
				'volume'			=> $instance['volume']
			);
			
			$i = 0;
			$length = count($video_data_property);
			$video_opt_array = '';
			foreach( $video_data_property as $option => $value ) {
				$i++;
				$zarez =  ( $i == $length ) ? '' : ', ';
				$video_opt_array .=  $option . ' : ' . $value . $zarez;
			}
			$video_options = 'data-property="{'. $video_opt_array .'}"';
			
		}

		
		?>

		<?php 
		// IF BACKGROUND IS "PARALLAXED"
		$parallax_atts = "";
		if( $instance['paralax'] && !$fixed ) {
			$bck_ratio		= $instance['back_ratio'] ? $instance['back_ratio'] : '0.1' ;
			$parallax_atts 	= 'data-parallax=true data-speed='.$bck_ratio.' data-direction=up';
		}
				
		// IMAGE OR VIDEO HOLDER: ?>
		<div class="row-image" <?php echo esc_js( $parallax_atts ); ?>>
			
			<?php 
			if( $instance['videoURL'] ) {
			
				echo '<div class="yt-video"' .$video_options. '></div>';
			
			}elseif( isset($instance['html5video']) ) {
				$html5video = $instance['html5video'];
				$vid_formats= array('.mp4', '.webm', '.ogg');
				$html5video = str_replace( $vid_formats ,'',$html5video);
				if ( $html5video && !$instance['videoURL'] ) {?>

				<video<?php echo $autoplay ? ' autoplay' : ''; echo $loop ? ' loop' : ''; echo $mute ? ' muted' : '';?> >
					<source src="<?php echo $html5video; ?>.mp4" type="video/mp4" />
					<source src="<?php echo $html5video; ?>.webm" type="video/webm" />
					<source src="<?php echo $html5video; ?>.ogg" type="video/ogg" />
				</video>
				<?php } 
			} ?>
		
		</div>
		
		<?php
		$opacity = isset( $overlay_opacity ) ? $overlay_opacity : '100';
			
		// OVERLAY DIV
		echo $instance['overlay'] ? '<div class="overlay" style="background-color:'.$instance['overlay'].'; opacity: '. (int)$opacity / 100 .'; filter: alpha(opacity='.(int)$opacity.'); -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity='. (int)$opacity .');"></div>' : null;
		
		
		// IF SINGLE PROD. BLOCK IS USED AND HAVE ARROW:
		/* deprecated because of validator issues - enable to debug
		echo '<style type="text/css">';
		
		echo '#aq-block-'.$number.' .single-product-block.float_right .arrow { border-left-color:'.$instance['overlay'].'; opacity: '. (int)$opacity / 100 .' }';
		
		echo '#aq-block-'.$number.' .single-product-block.float_left .arrow { border-right-color:'.$instance['overlay'].'; opacity: '. (int)$opacity / 100 .' }';
		
		echo '</style>';
		 */
		
		echo $instance['full_width'] ? '<div class="full-width">' : '<div class="row">';
		
	}
	
	function after_block($instance) {
		extract($instance);
		echo '</div></div>';
	}
	
	
	function block_callback($instance) {
	
		$instance = is_array($instance) ? wp_parse_args($instance, $this->block_options) : $this->block_options;
		
		extract($instance);
		
		$row_order = $order;
		$col_size = absint(preg_replace("/[^0-9]/", '', $size));
		
		//row block header
		if(isset($template_id)) {
		
			$this->before_block($instance);
			
			//define vars
			$overgrid = 0; $span = 0; $first = false;
			
			//check if row has blocks inside it
			$blocks = aq_get_blocks($template_id);
			
			//outputs the blocks
			if($blocks) {
			
				foreach($blocks as $key => $child) {
					global $aq_registered_blocks;
					
					extract($child);
					
					if(class_exists($id_base)) {
						//get the block object
						$block = $aq_registered_blocks[$id_base];
						
						//insert template_id into $child
						$child['template_id'] = $template_id;
						
						//display the block
						if($parent == $row_order) {
							
							$child_col_size = absint(preg_replace("/[^0-9]/", '', $size));
							
							$overgrid = $span + $child_col_size;
							
							if($overgrid > $col_size || $span == $col_size || $span == 0) {
								$span = 0;
								$first = true;
							}
							
							if($first == true) {
								$child['first'] = true;
							}
							
							$block->block_callback($child);
							
							$span = $span + $child_col_size;
							
							$overgrid = 0; //reset $overgrid
							$first = false; //reset $first
						}
					}
				}
			} 
			
			$this->after_block($instance);
			
		} else {
			//show nothing
		}
	}
	
}