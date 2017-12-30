<?php
/** A simple rich textarea block **/
class AS_Editor_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => __('AS Visual Editor', 'sequoia'),
			'size' => 'span6',
		);
		
		//create the block
		parent::__construct('as_editor_block' , $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'title' => '',
			'content' => '',
			'padding' => ''
		);
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		
		?>
		<p class="description">
			<label for="<?php echo $this->get_field_id('title') ?>">
				Title (optional)
				<?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
			</label>
		</p>
		
		<p class="description">
			<label for="<?php echo $this->get_field_id('content') ?>">
				Content
				<?php 
				$args = array (
					
				   // 'tinymce'       => true,
				    'quicktags'     => true,
				    'textarea_name' => $this->get_field_name('content')
				);
				wp_editor( htmlspecialchars_decode($content), $this->get_field_id('content'), $args );
				?>
			</label>
		</p>
		<p class="description">
			<label for="<?php echo $this->get_field_id('padding') ?>">
				Padding
				<?php echo aq_field_input('padding', $block_id, $padding, $size = 'full') ?>
			</label>

		</p>
		<p class="description">Set the padding around the whole block. Use value and unit (10px, 2em, 2 rem...). If set multiple values it will pply in this manner - top, right, bottom, left</p>
		<?php
	}
	
	function block($instance) {
		
		extract($instance);
		
		echo '<div class="editor-block"'. ($padding ? ' style="padding:'.$padding.';"' : '') .'>';
		
		if($title) echo '<h4 class="aq-block-title">'.strip_tags($title).'</h4>';
		
		echo wpautop(do_shortcode(htmlspecialchars_decode($content)));
		
		echo '</div>';
	}
	
}