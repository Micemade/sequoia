<?php
/** 
 * Maps block
 * 
 * get map based on Latitude and Longitude
**/
class AS_Contact_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Contact form',
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('as_contact_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'title'			=> '',
			'subtitle'		=> '',
			'sub_position'	=> 'bellow',
			'title_style'	=> 'center',
			'contact_email'	=> get_option('admin_email') ? get_option('admin_email') : '',
			'attach_id'		=> '',
			'img_format'	=> 'as-landscape',
			'text'			=> '',
			'wp_autop'		=> 0,
			'css_classes'	=> '',
		);
		
		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
				
		?>

		<div class="description half">
			<label for="<?php echo $this->get_field_id('title') ?>">
				Block title<br/>
				
				<?php echo aq_field_input('title', $block_id, $title ) ?>
			
			</label>
		</div>
		
		<div class="description half last">
			<label for="<?php echo $this->get_field_id('subtitle') ?>">
				Block subtitle (optional)<br/>
				
				<?php echo aq_field_input('subtitle', $block_id, $subtitle ) ?>
			
			</label>
		</div>
		
		<div class="description third">
			
			<label for="<?php echo $this->get_field_id('title_style') ?>">Block title style</label><br/>
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
		
			<label for="<?php echo $this->get_field_id('sub_position') ?>">
			Position of the subtitle</label><br/>
			
			<?php 
			$sub_options = array(
				'above'		=> 'Above heading',
				'bellow'	=> 'Bellow heading',
			);
			echo aq_field_select('sub_position', $block_id, $sub_options, $sub_position, $block_id); ?>
		
		</div>
		
		
		<div class="description third last">
			<label for="<?php echo $this->get_field_id('contact_email') ?>">
				Recipient email address (required)<br/>
				
				<?php echo aq_field_input('contact_email', $block_id, $contact_email ) ?>
			
			</label>
		</div>
		
		<div class="description third">
			
			<label for="<?php echo $this->get_field_id('attach_id') ?>">Location image (optional)</label>	
			
			<br /><br />
			
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
			
			<?php echo as_field_upload('attach_id', $block_id, $attach_id, 'thumbnail'); ?>
	
		</div>	
		
		<div class="description third">
			<label for="<?php echo $this->get_field_id('img_format') ?>">Image format</label>
			<br/>
			<?php
			$img_format_arr = array(
				'thumbnail'		=> 'Thumbnail',
				'medium'		=> 'Medium',
				'as-portrait'	=> 'Sequoia portrait',
				'as-landscape'	=> 'Sequoia landscape',
				'large'			=> 'Large',
				'full'			=> 'Full'
				);
			echo aq_field_select('img_format', $block_id, $img_format_arr, $img_format); 
			?>
			
		</div>
		
		<div class="description third last">
			<label for="<?php echo $this->get_field_id('text') ?>">
				Location description
				<?php echo aq_field_textarea('text', $block_id, $text, $size = 'full') ?>
			</label>
			<br />
			<label for="<?php echo $this->get_field_id('wp_autop') ?>">
				<?php echo aq_field_checkbox('wp_autop', $block_id, $wp_autop) ?>
				Do not create the paragraphs automatically. <code>"wpautop"</code> disable.
			</label>
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
		
		extract($instance);
		
		####################  HTML STARTS HERE: ###########################
		echo $css_classes ? '<div class="'.esc_attr($css_classes).'">' : null;
		?>
		
		<div class="header-holder <?php echo $title_style; ?>">
		
		<?php 
		echo ( $subtitle && $sub_position == 'above' ) ? '<div class="block-subtitle above '. $title_style .'">' . $subtitle . '</div>' : ''; 
		
		echo $title ? '<h2 class="block-title '. $title_style .'" style="margin-bottom: 1em">' . $title . '</h2>' : '';
		echo ( $subtitle && $sub_position == 'bellow' ) ? '<div class="block-subtitle bellow '. $title_style .'">' . $subtitle . '</div>' : ''; 
		?>
		
		</div>
		
		<?php 
		$additional = ( $attach_id || $text ) ? true : false; // if additional text and/or image
		?>
		
		<?php echo $additional ? '<div class="row">' : ''; ?>
		
		<div id="contact-block-<?php echo $block_id;?>" class="contact-form <?php echo $additional ? 'small-12 medium-6 column' : null; ?>">

           <div class="inner">

		   <div id="success" class="emailform-message success clearfix" style="display:none;"><?php echo __('Your email has been sent! Thank you!','sequoia'); ?>
			<button type="button" class="close" data-dismiss="alert"><span class="fs" aria-hidden="true" data-icon="&#xe09f;"></span></button>
			</div>

			<div id="bademail" class="emailform-message error clearfix" style="display:none;"><?php echo __('Please enter your name, a message and a valid email address.','sequoia'); ?><button type="button" class="close" data-dismiss="alert"><span class="fs" aria-hidden="true" data-icon="&#xe09f;"></span></button>
			</div>
			
			<div id="badserver" class="emailform-message error clearfix" style="display:none;"><?php echo __('Your email failed. Try again later.','sequoia'); ?><button type="button" class="close" data-dismiss="alert"><span class="fs" aria-hidden="true" data-icon="&#xe09f;"></span></button>
			</div>

			<div class="clearfix"></div>
			
			<form id="contact" action="<?php echo get_template_directory_uri(); ?>/sendmail.php" method="post" class="clearfix">
			
				<p class="name">
				<label for="name"><?php echo __('Your name:','sequoia'); ?> *</label>
					<input type="text" id="nameinput" class="contact_input" name="name" value=""/>
				</p>
				
				<p class="email">
				<label for="email"><?php echo __('Your email:','sequoia'); ?> *</label>
					<input type="text" id="emailinput" class="contact_input" name="email" value=""/>
				</p>
				
				<p class="message">
				<label for="comment"><?php echo __('Your message:','sequoia'); ?> *</label>
					<textarea cols="20" rows="7" id="commentinput" class="contact_input" name="comment"></textarea>
				</p>
				
				<p class="submit">
				<input type="submit" id="submitinput" name="submit" class="submit button small" value="<?php echo __('Send message','sequoia'); ?>"/>
				</p>
				
				<input type="hidden" id="receiver" name="receiver" value="<?php echo strhex( $contact_email ); ?>" />
				
			</form>

			</div>
		
		</div>  
		
		<?php echo $additional ? '<div class="small-12 medium-6 column contact-additional">' : null; ?>
			
			<div class="inner">
			
				<?php if( $attach_id ) { ?>
				<div class="image">
					<?php 
					$attr = array(
						'class' => 'as-hover',
						'title'	=> $title ? $title : '',
						'alt'	=> $title ? $title : ''
					);
					
					echo '<div class="entry-image">' . wp_get_attachment_image( $attach_id, $img_format, false,  $attr ) . '</div>'; 
					
					?>
				</div>
				<?php }; ?>
			
				<?php if( $text ) { ?>
				<div class="location-description">
				
					<?php 
					$wp_autop = ( isset($wp_autop) ) ? $wp_autop : 0;
					if($wp_autop == 1){
						echo do_shortcode(htmlspecialchars_decode($text));
					}
					else {
						echo wpautop(do_shortcode(htmlspecialchars_decode($text)));
					}
					?>
					
				</div>
				<?php }; ?>
		
			</div>
		
		<?php echo $additional ? '</div>' : null; // end .small-12 medium-6 ?>
		
		<?php echo $additional ? '</div>' : null; // end .row ?>
		
		<?php
		####################  HTML ENDS HERE: ###########################
		echo $css_classes ? '</div>' : null;
		?>
		
		<?php
		if ( !wp_script_is( 'as_contactform', 'enqueued' )) {
		
			wp_register_script( 'as_contactform', get_template_directory_uri() . '/js/as_contactform.min.js');
			wp_enqueue_script( 'as_contactform' );
		
			?>		
			<script type="text/javascript">
			var $j = jQuery.noConflict();
			$j(document).ready(function() {
			
				$j('#contact').ajaxForm(function(data) {
					if (data==1){
						$j('#success').fadeIn("slow");
						$j('#bademail').fadeOut("slow");
						$j('#badserver').fadeOut("slow");
						$j('#contact').resetForm();
					}
					else if (data==2){
						$j('#badserver').fadeIn("slow");
					}
					else if (data==3)
					{
						$j('#bademail').fadeIn("slow");
					}
				});
				$j('.contact-form').find('button').click(function() {
					$j(this).parent().fadeToggle();
				});
				
			});
			</script>
		
		<?php
		} //end if ( !wp_script_is ...
	
	} // end funct. block
	
	
}