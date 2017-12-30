<?php
/** 
 *	A block for Revolution Slider 
 *
 **/
class AS_Revolution_Slider extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Revolution slider',
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('as_revolution_slider', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'sliders' => ''
		);
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		
		?>
		
		<p class="description">
			
			<label for="<?php echo $this->get_field_id('sliders') ?>">Select Revolution Slider</label>
			
			<?php
			if ( class_exists( 'RevSliderAdmin' ) ) {

				/* get slider aliases array */
				$slider = new RevSlider();
				$arrSliders = $slider->getAllSliderAliases();
				
				/* has slides */
				if ( ! empty( $arrSliders ) ) {
					
					$slider_arr = array();
					 
					foreach ( $arrSliders as $id => $alias ) {
						$slider_arr[$alias] =  $alias  ;
					}
									
					echo aq_field_select('sliders', $block_id, $slider_arr, $sliders); 
					
				
				} else {
					echo '<p class="description">' . __( 'No Sliders Found', 'sequoia' ) . '</p>';
				}
				echo '</select>';
				
			} else {
			
				echo '<p class="description" style="color: red;">' . __( 'Sorry! Revolution Slider is not Installed or Activated', 'sequoia' ). '</p>';
				
			}
			?>

		</p>
		
		<?php
	}
	
	function block($instance) {
		
		$defaults = array(
			'sliders' => ''
		);
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		
		$sliders = '[rev_slider '.$sliders.']';
		
		echo do_shortcode($sliders);
		
	}
	
}
