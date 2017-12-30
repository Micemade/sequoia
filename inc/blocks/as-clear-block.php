<?php
/** Simple "Clear" block - AS replacement of incomplete(?) plugin's block ..
 * 
 *	Clear the floats vertically
 *	Optional to use horizontal lines/images
**/
class AS_Clear_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Clear',
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('as_clear_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'horizontal_line'	=> 'none',
			'line_color'		=> '#353535',
			'line_width'		=> '',
			'margin'			=> '15'
		);
		
		$line_options = array(
			'none'			=> 'None',
			'solid'			=> 'Single',
			'double'		=> 'Double',
			'dotted'		=> 'Dotted',
			'dashed'		=> 'Dashed',
		);
		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		
		$line_color = isset($line_color) ? $line_color : '#353535';
		
		?>
		<p class="description">
			<?php _e('Use this block to clear the floats between two or more separate blocks vertically.', 'sequoia') ?>
		</p>
		
		<div class="description fourth">
			
			<label for="<?php echo $this->get_field_id('line_color') ?>">Horizontal line type</label><br/>
				
			<?php echo aq_field_select('horizontal_line', $block_id, $line_options, $horizontal_line, $block_id); ?>
			
		</div>
		
		<div class="description fourth ">
			
			<label for="<?php echo $this->get_field_id('line_color') ?>">Line color</label><br/>
			
			<?php echo aq_field_color_picker('line_color', $block_id, $line_color, $defaults['line_color']) ?>
			
		</div>
				
		
		<div class="description fourth">
			
			<label for="<?php echo $this->get_field_id('line_width') ?>">Line width</label><br/>
				
			<?php echo aq_field_input('line_width', $block_id, $line_width, 'min', 'number') ?> px
			
		</div>
		
		<div class="description fourth last">
			
			<label for="<?php echo $this->get_field_id('margin') ?>">Margin (top AND bottom)</label><br/>
				
			<?php echo aq_field_input('margin', $block_id, $margin, 'min', 'number') ?> px
			
		</div>
		<?php
		
	}
	
	function block($instance) {
		extract($instance);
		
		switch( $horizontal_line ) {
			case 'none':
				
				echo '<div id="'.$block_id.'" class="clearfix" style="'. ($margin ? 'margin:'.$margin.'px 0;' : '') .'; height:0;"></div>';
			break;
				
			case 'solid':
			case 'double':
			case 'dotted':
			case 'dashed':
				echo '<style type="text/css">#'.$block_id.' { ';
				echo $margin			? 'margin:'.$margin.'px 0;' : '';
				echo $line_width		? 'border-bottom-width: '.$line_width.'px;' : null;
				echo $line_color		? 'border-bottom-color:'.$line_color.';' : null;
				echo $horizontal_line	? 'border-bottom-style:'.$horizontal_line.';' : null;
				echo '}</style>';
				
				echo '<div id="'.$block_id.'" class="clearfix"></div>';
				
			break;
			
			
		}

		
	}
	
}