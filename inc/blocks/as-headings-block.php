<?php
/** 
 * A simple headings block
 *
 **/
if(!class_exists('AS_Headings_Block')) {
 
class AS_Headings_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name'	=> 'Headings',
			'size'	=> 'span12',
		);
		
		//create the block
		parent::__construct('as_headings_block', $block_options);
	}
	
	function animations_array() {
		
		include ( get_template_directory() .'/inc/functions/animations-icons-arrays.php' );
		return $block_enter_anim_arr;

	}
	
	function form($instance) {
		
		$defaults = array(
			'title'			=> '',
			'h_size'		=> 'h2',
			'subtitle'		=> '',
			'sub_position'	=> 'above',
			'title_style'	=> 'center',
			'enter_anim'	=> 'fadeIn',
			'anim_delay'	=> 0,
			'css_classes'	=> ''
			
		);
		
		$h_options = array(
			'h1' => 'Heading 1',
			'h2' => 'Heading 2',
			'h3' => 'Heading 3',
			'h4' => 'Heading 4',
			'h5' => 'Heading 5',
			'h6' => 'Heading 6',
		);
		
		$sub_options = array(
			'above'		=> 'Above heading',
			'bellow'	=> 'Bellow heading',
		);
		
		
		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		
		?>
		<div class="description third">
		
			<label for="<?php echo $this->get_field_id('title') ?>">Heading text</label><br />
				
			<?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
			
		</div>
		
		<div class="description third">
		
			<label for="<?php echo $this->get_field_id('h_size') ?>">
			Pick a heading size</label><br/>
			
			<?php echo aq_field_select('h_size', $block_id, $h_options, $h_size, $block_id); ?>
			
		</div>
		
		<div class="description third last">
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
		
		<div class="description third">
			
			<label for="<?php echo $this->get_field_id('enter_anim') ?>">Viewport enter animation</label><br/>	
			<?php
			echo aq_field_select('enter_anim', $block_id, $this->animations_array(), $enter_anim ); 
			?>
			
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('anim_delay') ?>">Animation delay (milisec.)</label><br/>	
			
			<?php echo aq_field_input('anim_delay', $block_id, $anim_delay, $type="number") ;?>
			
			<p class="description">Type the value for delay of block animation delay. Use the miliseconds ( 1second = 1000 miliseconds; example: 100 for 1/10th of second )</p>
			
		</div>
		
		
		<div class="description third">
		
			<label for="<?php echo $this->get_field_id('subtitle') ?>">Optional sub title</label><br />
				
			<?php echo aq_field_input('subtitle', $block_id, $subtitle, $size = 'full') ?>
			
		</div>
		
		<div class="description third last">
		
			<label for="<?php echo $this->get_field_id('sub_position') ?>">
			Position of the subtitle</label><br/>
			
			<?php echo aq_field_select('sub_position', $block_id, $sub_options, $sub_position, $block_id); ?>
			
		</div>
		
		<hr>
		
		<div class="description">	
			<label for="<?php echo $this->get_field_id('css_classes') ?>">Additional CSS classes</label>
			<?php echo aq_field_input('css_classes', $block_id, $css_classes, $size = 'full') ?>
		
			<p class="description small">Add custom css classes, defined in child theme css file, or custom css (in Theme options). Multiple classes can be added, separated by space.</p>
		</div>	
		
		<?php
	}
	
	function block($instance) {
		
		global $border_decor;
		
		extract($instance);
		
		if ( $enter_anim != 'none' && !wp_style_is( 'animate', 'enqueued' )) {
				
			wp_register_style( 'animate', get_template_directory_uri() . '/css/animate.css');
			wp_enqueue_style( 'animate' );
			
		}
		?>
		
		<div id="heading-block-<?php echo $block_id; ?>" class="heading-block inner-wrapper<?php echo ($enter_anim != 'none') ? ' to-anim' :'';  ?> <?php echo $title_style; ?> <?php echo $css_classes ? $css_classes : ''; ?>">
		
			<?php
			echo ( $subtitle && $sub_position == 'above' ) ? '<div class="block-subtitle above '. $title_style .'">'.$subtitle.'</div>' : '';
			
			if( $title ) echo '<'.$h_size.' class="block-title '. $title_style .'">'.strip_tags($title).'</'.$h_size.'>';
			
			echo ( $subtitle && $sub_position == 'bellow' ) ? '<div class="block-subtitle bellow '. $title_style .'">'.$subtitle.'</div>' : '';
			?>
			<div class="clearfix"></div>
		
		</div>
		
		<?php $delay = $anim_delay ? $anim_delay : 100 ?>
			
		<script>
		jQuery(document).ready( function($) {
			
			var thisBlock = $('#heading-block-<?php echo $block_id;?>');
			
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
	} // end func block
	
} // end class

} // end class_exists
