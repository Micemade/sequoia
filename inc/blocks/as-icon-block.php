<?php
/** 
 *	Icons block 
 *	creating icons with different style
 *	
 **/

if(!class_exists('AS_Icon_Block')) {
	
	class AS_Icon_Block extends AQ_Block {
		
		//set and create block
		function __construct() {
			$block_options = array(
				'name' => 'Icon block',
				'size' => 'span4',
			);
			
			//create the block
			parent::__construct('as_icon_block', $block_options);
		}
		
		function animations_array() {
			
			include ( get_template_directory() .'/inc/functions/animations-icons-arrays.php' );
			return $block_enter_anim_arr;

		}
		
		function form($instance) {
			
			
			$defaults = array(
				'title'			=> '',
				'content'		=> '',
				'icon'			=> '&#x21;',
				'icon_size'		=> '2', // em
				'icon_padding'	=> '5', // %
				'icon_color'	=> '#999',
				'border_size'	=> '0', // px
				'border_color'	=> '#999',
				'border_radius'	=> '0', //px
				'background'	=> '#fff',
				'transparent'	=> false,
				'layout_style'	=> 'centered',
				'block_color'	=> '#eee',
				'block_opacity'	=> '100',
				'block_border'	=> 'solid',
				'icon_anim'		=> 'bounce',
				'enter_anim'	=> 'fadeIn',
				'anim_delay'	=> 100,
				'content'		=> '',
				'wp_autop'		=> 0,
				'button_text'	=> '',
				'button_url'	=> '',
				'target'		=> '',
				'css_classes'	=> '',
			);
			$instance = wp_parse_args($instance, $defaults);
			extract($instance);
			
			?>

			<div class="description half icon-field">
				
				<div class="icon-glyph">
				
					<label for="<?php echo $this->get_field_id('icon') ?>"></label>
					<?php echo as_hidden_input('icon', $block_id, $icon, $type = 'hidden')?>
				
				</div>
				
				<div class="preview-box-holder">
					
					<?php 
					$box_inl_style  = 'style="';
					$box_inl_style .= $block_color ? 'background-color:'. $block_color.'; ' : '';
					$box_inl_style .= $block_opacity ? 'opacity:'. $block_opacity/100 .'; ' : '';
					$box_inl_style .= '"';
					?>
					
					<div class="preview-box" <?php echo $box_inl_style; ?>>
					
					<?php
					$inline_style  = 'style=" ';
					$inline_style .= $icon_size ? 'font-size: '.$icon_size.'em; ' : '';
					$inline_style .= $icon_color ? 'color: '.$icon_color.'; ' : '';
					$inline_style .= $border_size ? 'border-width: '.$border_size.'px; ' : 'border-width:0px;';
					$inline_style .= $border_color ? 'border-color: '.$border_color.'; ' : '';
					$inline_style .= $border_radius ? 'border-radius: '.$border_radius.'px; ' : '';
					$inline_style .= ($background && !$transparent) ? 'background-color: '.$background.'; ' : '';
					$inline_style .= $icon_padding ? 'padding: '.$icon_padding.'px; ' : '';
					$inline_style .= '"';
					?>
						
						<div id="preview-icon" class="<?php echo $icon; ?>" aria-hidden="true" <?php echo $inline_style; ?> data-backcolor="<?php echo $background; ?>"></div>
					
					</div>
				</div>
				
				<hr class="clearfix" />
				
				<a href="#" class="toggle-icon-controls button" rel="image">Colors and sizes show/hide</a>
				<div class="clearfix"></div>
				
				<?php 
				/**
				*	ICON PROPERTIES CONTROL
				*
				*	important: css classes slider-control and icon-size(padding etc.) 
				*	needed for live icon preview. Needed for colorpicker and slider
				*	icon properties size, padding, and color (icon, back and border)
				*
				*	JS file controlling is aqpb-fields.js in theme inc/blocks direcory
				*
				*/
				
				// ICON SIZES CONTROLS : ?>
				
				<div class="slider-controls half icon-size">
					
					<label for="<?php echo $this->get_field_id('icon_size') ?>">Icon size <span><?php echo $icon_size . 'em'; ?></span></label>
					
					<?php echo as_hidden_input('icon_size', $block_id, $icon_size, $type = 'hidden')?>
					
					<div class="slider-for-icon"></div>

				</div>
						
				<div class="slider-controls half icon-padding">
				
					<label for="<?php echo $this->get_field_id('icon_padding') ?>">Icon padding <span><?php echo $icon_padding . 'px'; ?></span></label>
					
					<?php echo as_hidden_input('icon_padding', $block_id, $icon_padding, $type = 'hidden')?>
					
					<div class="slider-for-icon"></div>
					
				</div>				
						
				<div class="slider-controls half border-width">
				
					<label for="<?php echo $this->get_field_id('border_size') ?>">Border size <span><?php echo $border_size . 'px'; ?></span></label>
					
					<?php echo as_hidden_input('border_size', $block_id, $border_size, $type = 'hidden')?>
					
					<div class="slider-for-icon"></div>
					
				</div>
				
				
				<div class="slider-controls half border-radius">
					
					<label for="<?php echo $this->get_field_id('border_radius') ?>">Border radius <span><?php echo $border_radius . 'px'; ?></span></label>
					
					<?php echo as_hidden_input('border_radius', $block_id, $border_radius, $type = 'hidden')?>
					
					<div class="slider-for-icon"></div>

				</div>
			
				
				<?php // ICON COLOR CONTROLS :?>
				
				
				<div class="slider-controls icon-color">
				
					<label for="<?php echo $this->get_field_id('icon_color') ?>">
					<?php echo __('Icon color','sequoia'); ?>
					</label>
					<?php echo aq_field_color_picker('icon_color', $block_id, $icon_color ) ?>	

				</div>
				
				<div class="slider-controls icon-border-color">
				
					<label for="<?php echo $this->get_field_id('border_color') ?>">
					<?php echo __('Border color','sequoia'); ?>
					</label>
					<?php echo aq_field_color_picker('border_color', $block_id, $border_color ) ?>
				
				</div>
				
				<div class="slider-controls icon-background-color">

					<label for="<?php echo $this->get_field_id('background') ?>">
					<?php echo __('Icon background color','sequoia'); ?>
					</label>
					<?php echo aq_field_color_picker('background', $block_id, $background ) ?>
					
				</div>

				<div class="slider-controls icon-transparent">
			
					<label for="<?php echo $this->get_field_id('transparent') ?>">No icon background ?</label>
					<?php echo aq_field_checkbox('transparent', $block_id, $transparent); ?>
					
				</div>
				
				<hr class="clearfix" />
				
				<div class="slider-controls block-background-color">
				
					<label for="<?php echo $this->get_field_id('block_color') ?>">Block background color
					</label>
					<?php echo aq_field_color_picker('block_color', $block_id, $block_color, $block_color) ?>
				</div>
				
				<div class="slider-controls half block-opacity">
					
					<label for="<?php echo $this->get_field_id('block_opacity') ?>">Block opacity <span><?php echo $block_opacity ; ?></span></label>
					
					<?php echo as_hidden_input('block_opacity', $block_id, $block_opacity, $type = 'hidden')?>
					
					<div class="slider-for-icon"></div>

				</div>
				
				<hr>
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('block_border') ?>">Block border style</label><br/>	
				<?php
				$border_arr = array(
					'none'		=> 'None',
					'solid'		=> 'Solid',
					'dashed'	=> 'Dashed',
					'dotted'	=> 'Dotted',
					'double'	=> 'Double'
					);
				echo aq_field_select('block_border', $block_id, $border_arr, $block_border); 
				?>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('icon_anim') ?>">Icon animation</label><br/>	
				<?php
				$icon_anim_arr = array(
					'none'			=> 'None',
					'bounce'		=> 'Bounce',
					'flash'			=> 'Flash',
					'pulse'			=> 'Pulse',
					'rubberband'	=> 'RubberBand',
					'shake'			=> 'Shake',
					'swing'			=> 'Swing',
					'tada'			=> 'TaDa',
					'wobble'		=> 'Wobble',

					);
				echo aq_field_select('icon_anim', $block_id, $icon_anim_arr, $icon_anim); 
				?>
				
				
				<label for="<?php echo $this->get_field_id('enter_anim') ?>">Viewport enter animation</label><br/>	
				<?php echo aq_field_select('enter_anim', $block_id, $this->animations_array(), $enter_anim ); ?>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('anim_delay') ?>">Animation delay (milisec.)</label><br/>	
				
				<?php echo aq_field_input('anim_delay', $block_id, $anim_delay, $type="number") ;?>
				
				<p class="description">Type the value for delay of block animation delay. Use the miliseconds ( 1second = 1000 miliseconds; example: 100 for 1/10th of second )</p>
				
				<hr>
				
			</div>
			

			<a href="#" class="toggle-icon-choice button" rel="image">Icons show/hide</a>
			
			<div class="description half icons-controls">
				
				<div class="glyphs">
				
					<?php include('as-icons-html.php'); ?>
				
				</div>
				
			</div>	
			
			<hr>
			
			<a href="#" class="toggle-layout-text button" rel="image">Layout, text show/hide</a>	
		
			<div class="description half layout-text-controls">
				
				<label for="<?php echo $this->get_field_id('layout_style') ?>">Layout style</label><br/>
					
				<?php 
				$layout_style_options = array(
					'centered'		=> 'Centered',
					'float_left'	=> 'Float left',
					'float_right'	=> 'Float right',
				);
				echo aq_field_select('layout_style', $block_id, $layout_style_options, $layout_style) ?>
				
				
				<label for="<?php echo $this->get_field_id('title') ?>">
					Title (optional)<br/>
					<?php echo aq_field_input('title', $block_id, $title) ?>
				</label>
			
				<label for="<?php echo $this->get_field_id('content') ?>">
					Icon Block Text<br/>
					<?php echo aq_field_textarea('content', $block_id, $content) ?>
				</label>
	
				<p class="clearfix description slider-controls">NOTE: To control font color, icon block can be placed inside ROW block and control font colors from ROW block settings.</p>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('wp_autop') ?>">
				<?php echo aq_field_checkbox('wp_autop', $block_id, $wp_autop) ?>
				Do not create the paragraphs automatically. <code>"wpautop"</code> disable.
				</label>
				<br/>
				
			
				<label for="<?php echo $this->get_field_id('button_text') ?>">Button text</label>
				<?php echo aq_field_input('button_text', $block_id, $button_text, $size = 'full') ?>

				
				<label for="<?php echo $this->get_field_id('button_url') ?>">Button link</label>
				<?php echo aq_field_input('button_url', $block_id, $button_url, $size = 'full') ?>
				
			
				<label for="<?php echo $this->get_field_id('target') ?>">Open in new tab/window</label><br />
				<?php echo aq_field_checkbox('target', $block_id, $target); ?>

				
				<p class="description clearfix">To display button, BOTH button text and button link must be entered. If only button link is entered, the link will be applied TO ICON</p>

			
			</div>	
			
			<hr>
		
			<div class="description">	
				<label for="<?php echo $this->get_field_id('css_classes') ?>">Additional CSS classes</label>
				<?php echo aq_field_input('css_classes', $block_id, $css_classes, $size = 'full') ?>
			
				<p class="description small">Add custom css classes, defined in child theme css file, or custom css (in Theme options). Multiple classes can be added, separated by space.</p>
			</div>	
			
			<div class="clearfix"><hr></div>
			
			<?php
			
		}
		
		function block($instance) { // frontend output
			
			extract($instance);
	
			if ( $enter_anim != 'none' && !wp_style_is( 'animate', 'enqueued' )) {
				
				wp_register_style( 'animate', get_template_directory_uri() . '/css/animate.css');
				wp_enqueue_style( 'animate' );
				
			}
			
			
			$css = '<style scoped>';
			/* 
			// icon properties:
			$css .= '#icon-'. $block_id .' .icon {' ;
			$css .= $icon_size ? 'font-size: '.$icon_size.'em; ' : '';
			$css .= $icon_color ? 'color: '.$icon_color.'; ' : '';
			$css .= $border_size ? 'border-width: '.$border_size.'px; ' : 'border-width:0px;';
			$css .= $border_color ? 'border-color: '.$border_color.'; ' : '';
			$css .= $border_radius ? 'border-radius: '.$border_radius.'px; ' : '';
			$css .= $icon_padding ? 'padding: '.$icon_padding.'px; ' : '';
			$css .= 'border-style: solid;';
			$css .= ($background && !$transparent) ? 'background-color: '.$background.'; ' : '';
			$css .= '}';
			 
			// icon background color:
			$css .= '#icon-block-'. $block_id .' .icon-back-color { background-color: '. ($block_color ? $block_color : 'transparent') .';';
			$css .= $block_opacity ? 'opacity: '. $block_opacity/100 .'; filter: alpha(opacity='.$block_opacity.');' : '';
			$css .= '}';
			*/
			
			$double_border = ($block_border == 'double') ? 'border-width: 4px;' : '';
			$css .='#icon-block-'.$block_id.':before { border-style: '.$block_border.'; '.$double_border.'  }';
			$css .= '</style>';
			
			echo $css;
			
			$trgt = $target ? ' target="_blank"' : '';
			?>
			
			<div id="icon-block-<?php echo $block_id;?>" class="icon-block inner-wrapper<?php echo $layout_style ? ' ' . $layout_style : null; ?> <?php echo ($enter_anim != 'none') ? 'to-anim' :''; echo $css_classes ? ' '.$css_classes : ''; ?>">
			
				<?php
				// SCOPED CSS
				$ic_back_css = '<style scoped>';
				$ic_back_css .= '#icon-block-'. $block_id .' .icon-back-color { background-color: '. ($block_color ? $block_color : 'transparent') .';';
				$ic_back_css .= $block_opacity ? 'opacity: '. $block_opacity/100 .'; filter: alpha(opacity='.$block_opacity.');' : '';
				$ic_back_css .= '}';
				$ic_back_css .= '</style>';
				echo $ic_back_css;
				?>
				
				<div class="icon-back-color"></div>
				
				<div id="icon-<?php echo $block_id;?>" class="icon-block-table">
					
					<?php ?>
					
					<?php echo (!$button_text && $button_url) ? '<a href="'. esc_url($button_url) .'"'.$trgt.'>': null; ?>
						
					<?php // SCOPED CSS
					$icon_css = '<style scoped>';
					$icon_css .= '#icon-'. $block_id .' .icon {' ;
					$icon_css .= $icon_size ? 'font-size: '.$icon_size.'em; ' : '';
					$icon_css .= $icon_color ? 'color: '.$icon_color.'; ' : '';
					$icon_css .= $border_size ? 'border-width: '.$border_size.'px; ' : 'border-width:0px;';
					$icon_css .= $border_color ? 'border-color: '.$border_color.'; ' : '';
					$icon_css .= $border_radius ? 'border-radius: '.$border_radius.'px; ' : '';
					$icon_css .= $icon_padding ? 'padding: '.$icon_padding.'px; ' : '';
					$icon_css .= 'border-style: solid;';
					$icon_css .= ($background && !$transparent) ? 'background-color: '.$background.'; ' : '';
					$icon_css .= '}';
					$icon_css .= '</style>';
					echo $icon_css;
					?>
					
					<div class="icon-block-cell">
						
						<div class="icon <?php echo $icon; ?>" aria-hidden="true"></div>

						<?php echo (!$button_text && $button_url) ? '</a>': null; ?>
						
					</div>
				</div>
				
				<?php
				
				if ( $title || $content ) {
				
				echo '<div class="icon-block-text">';
				
				echo $title ? '<h4>'.$title.'</h4>' : null;
				
				echo $content ? '<div class="content">' : null; 

					$wp_autop = ( isset($wp_autop) ) ? $wp_autop : 0;
					if( $wp_autop == 1 ){
						echo do_shortcode(htmlspecialchars_decode($content));
					}
					else {
						echo wpautop(do_shortcode(htmlspecialchars_decode($content)));
					}
					
				echo $content ? '</div>' : null; 
				
				
				if( $button_text && $button_url ) {
				
					echo '<a href="'. esc_url($button_url) .'" class="button"'.$trgt.'>'.$button_text.'</a>';
					
				}
				echo '</div>';
				
				}
				?>
			
				<div class="clearfix"></div>
			
			</div>
			
			<?php $delay = $anim_delay ? $anim_delay : 100 ?>
			
			<script>
			jQuery(document).ready( function($) {
				
				var thisBlock = $('#icon-block-<?php echo $block_id;?>');
				
				thisBlock.mouseover(
					function (){
						$(this).addClass('active');
						if( !window.isIE9 ) {
							$(this).find('.icon').addClass('animated <?php echo $icon_anim;?>');
						}
						 
					}
				).mouseout(
					
					function() {
						$(this).removeClass('active');
						$(this).find('.icon').removeClass('animated <?php echo $icon_anim;?>');
					}
				);
				

				
				if ( !window.isMobile && !window.isIE9 ) {

					thisBlock.waypoint(
					
						function(direction) {
							
							if( direction === "up" ) {	
								
								thisBlock.delay(100).removeClass('animated <?php echo $enter_anim;?>').addClass('to-anim');
								
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
	
		}// function block()
		
	} // class AS_Icon_Block
}