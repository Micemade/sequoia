<?php
/**
 *	AS_Button.
 *
 *	block and class for showing posts, portfolios.
 *	features - filtering and sorting items via jQuery Shuffle plugin
 */
class AS_Button extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Button',
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('as_button', $block_options);
	}
	
	function form($instance) {
				
		$defaults = array(
			'title'				=> '',
			'subtitle'			=> '',
			'button_float'		=> 'center',
			'button_style'		=> 'style1',
			'button_margin'		=> 1,
			'button_color'		=> '',
			'border_color'		=> '',
			'border_width'		=> 0,
			'text_color'		=> '',
			'button_label'		=> 'Read more',
			'button_url'		=> '',
			'target'			=> false,
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
		
		<hr>
		
		<div class="description third">
			<label for="<?php echo $this->get_field_id('button_float') ?>">
				Button float
			</label>	<br/>
			<?php
			$img_formats = array(
				'center'		=> 'Center',
				'float_left'	=> 'Float left',
				'float_right'	=> 'Float right'
				);
			echo aq_field_select('button_float', $block_id, $img_formats, $button_float); 
			?>	
			
			<p class="description">Affects also the title and subtitle floating ( if existing )</p>

			<hr>
			
			
			<label for="<?php echo $this->get_field_id('button_style') ?>">Button style</label><br/>	
			<?php
			$button_styles = array(
				'default'		=> 'Default',
				'smaller'		=> 'Smaller',
				'larger'		=> 'Larger',
				'extra_large'	=> 'Extra large'		
				);
			echo aq_field_select('button_style', $block_id, $button_styles, $button_style); 
			?>
		
		</div>
		
		<div class="description third">
		
			<div class="slider-controls half icon-size">
				
				<label for="<?php echo $this->get_field_id('button_margin') ?>">Button margin: <span><?php echo $button_margin . 'em'; ?></span></label>
				
				<?php echo as_hidden_input('button_margin', $block_id, $button_margin, $type = 'hidden')?>
				
				<div class="slider-for-icon"></div>

			</div>
			
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('button_color') ?>">Button color
			</label><br />
			<?php echo aq_field_color_picker('button_color', $block_id, $button_color ) ?>
			
			<div class="clearfix clear"></div>
			
			<label for="<?php echo $this->get_field_id('text_color') ?>">Text color
			</label><br />
			<?php echo aq_field_color_picker('text_color', $block_id, $text_color ) ?>
		
		</div>
		
		<div class="description third last">
		
			<label for="<?php echo $this->get_field_id('border_color') ?>">Border color
			</label><br />
			<?php echo aq_field_color_picker('border_color', $block_id, $border_color ) ?>
			
			<div class="slider-controls border-width">
				
					<label for="<?php echo $this->get_field_id('border_width') ?>">Border size <span><?php echo $border_width . 'px'; ?></span></label>
					
					<?php echo as_hidden_input('border_width', $block_id, $border_width, $type = 'hidden')?>
					
					<div class="slider-for-icon"></div>
					
				</div>
		
		</div>
		
		<hr>		

		
		<div class="description third">	
			<label for="<?php echo $this->get_field_id('button_label') ?>">Button label</label>
			<?php echo aq_field_input('button_label', $block_id, $button_label, $size = 'full') ?>
		</div>	
				
		<div class="description third">	
			<label for="<?php echo $this->get_field_id('button_url') ?>">Button link</label>
			<?php echo aq_field_input('button_url', $block_id, $button_url, $size = 'full') ?>
		</div>	
		
		<div class="description third last">
			
			<label for="<?php echo $this->get_field_id('target') ?>">Open in new tab/window</label><br />
			<?php echo aq_field_checkbox('target', $block_id, $target); ?>
		
		</div>
		
		<div class="clearfix clear"></div>
		
		<div class="description">	
			If either of these two fields are empty, the button "more" button won't show.
		</div>
		
		<hr />
		
		<div class="description">	
			<label for="<?php echo $this->get_field_id('css_classes') ?>">Additional CSS classes</label>
			<?php echo aq_field_input('css_classes', $block_id, $css_classes, $size = 'full') ?>
		
			<p class="description small">Add custom css classes, defined in child theme css file, or custom css (in Theme options). Multiple classes can be added, separated by space.</p>
		</div>	
		
	<?php
	
	}
	
	function block($instance) {
			
		extract($instance);	
		
		// BEGIN HTML:
		
		if( $button_margin ) {
		
			echo '<style scoped>#'.$block_id .'{ padding: '.$button_margin.'em; }</style>';

		}
		
		####################  HTML STARTS HERE: ###########################
		// if custom css classes:
		echo $css_classes ? '<div class="'.esc_attr($css_classes).'">' : null;
		
		echo $title ? '<h2 class="categories-block block-title '. $button_float .'">' . $title . '</h2>' : '';
		echo $subtitle ? '<div class="block-subtitle '. $button_float .' above">' . $subtitle . '</div>' : ''; 
		?>
		
		<div class="clearfix"></div>
			
		<div id="<?php echo $block_id; ?>" class="button-block">
			
			<?php
			if( $button_color || $text_color || $border_width || $border_color  ) {
	
				echo '<style scoped>';					
				echo '#'.$block_id .' .bottom-block-link a.button {';
				echo $button_color	?	'background-color:'. $button_color .';' : '';
				echo $text_color	?	'color:'. $text_color .';' : '';
				echo $border_width	?	'border: 1px solid;' : '';
				echo $border_width	?	'border-width: '.$border_width.'px; ' : '';
				echo $border_color	?	'border-color: '.$border_color.'; ' : '';
				echo '}';
				echo '</style>';
			}
			?>
					
			<?php if( $button_label && $button_url ) { ?>
			<div class="bottom-block-link <?php echo $button_float; ?>">
				
				<a href="<?php echo $button_url ; ?>" title="<?php echo esc_attr($button_label); ?>" class="button <?php echo $button_style; ?>" <?php echo $target ? 'target="_blank"' : '';?>>
					<?php echo esc_html($button_label); ?>
					
					
				</a>
				
			</div>
			<?php } //endif; $button_label ?>
			
			<div class="clearfix"></div>
			
		</div><!-- .content-block .cb-4 -->
		
		<?php
		####################  HTML ENDS HERE: ###########################
		echo $css_classes ? '</div>' : null;
		?>
		
		<?php
	
	
	}/// END func block
	
	function update($new_instance, $old_instance) {
		return $new_instance;
	}
} ?>