<?php
/* 
 * Aligator Studio One Pager Menu block 
 */
if(!class_exists('AS_Onepager_Menu')) {
	
	class AS_Onepager_Menu extends AQ_Block {
	
		function __construct() {
			$block_options = array(
				'name' => 'Onepager menu',
				'size' => 'span12',
			);
			
			//create the widget
			parent::__construct('AS_Onepager_Menu', $block_options);
			
			//add ajax functions
			add_action('wp_ajax_aq_block_menu_item_add_new', array($this, 'add_menu_item'));
			
		}
		
		function form($instance) {
		
			if ( !wp_script_is( 'select2', 'enqueued' )) {
				
				function enqueue_select2_onepager () {
				
					wp_register_script( 'select2', get_template_directory_uri() . '/js/select2/select2.min.js');
					wp_enqueue_script( 'select2' );
					
					wp_register_style( 'select2-css',get_template_directory_uri() . '/js/select2/select2.css','', '', 'all' );
					wp_enqueue_style( 'select2-css' );
				}
				
				add_action( 'admin_enqueue_scripts', 'enqueue_select2_onepager' );
				
			}
			
			
			
			$defaults	= array(
				'menu_items'	=> array(
					1 => array(
						'title'		=> 'New menu item',
						'content'	=> '',
						'external'	=> false,
						'icon'		=> ''
					)
				),
				'sticky'		=> true,
				'margin'		=> '',
				'clone'			=> false,
				'back_color'	=> '',
				'color'			=> '#333333',
				'menu_style'	=> 'style1',
				'menu_align'	=> 'centered',
				
				'img_format'	=> 'medium',
				'attach_id'		=> '',
				'title'			=> '',
				'logo_align'	=> 'center',
				'logo_width'	=> '100px',
				'logo_padding'	=> '15px 20px',
				'link'			=> '',
				'target'		=> '',
				'css_classes'	=> ''
			);
			
			$instance = wp_parse_args($instance, $defaults);
			extract($instance);
			
			
			?>
			<div class="description cf">
				
				<p class="description notice"><strong>Copy the ROW ID value ( from the top of each row block ) AFTER you save the template, to "Menu item anchor link".</strong></p>
				
				<ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
					<?php
					$menu_items = is_array($menu_items) ? $menu_items : $defaults['menu_items'];
					$count = 1;
					foreach($menu_items as $menu_item) {	
						$this->create_menu_item($menu_item, $count);
						$count++;
					}
					?>
				</ul>
				<p></p>
				<a href="#" rel="menu_item" class="aq-sortable-add-new button"><?php _e('Add new menu item','sequoia'); ?></a>
				<p></p>
			</div>
			
			<hr>
			
			<div class="description third">
			
				<label for="<?php echo $this->get_field_id('sticky') ?>"><?php _e('Make menu sticky ?','sequoia');?><br><small>Not applied if copied to side menu/header menu</small></label>
				
				<?php echo aq_field_checkbox('sticky', $block_id,  $sticky) ;?>
				
				
				<div class="clearfix"></div>

				
				<label for="<?php echo $this->get_field_id('clone') ?>"><?php _e('Copy menu to side menu / header menu ?','sequoia');?></label><br />
				
				<?php //echo aq_field_checkbox('clone', $block_id,  $clone) ;?>
				<?php
				$clone_arr = array(
					''			=> '',
					'above'		=> 'Above the main menu',
					'bellow'	=> 'Bellow the main menu',
					'replace'	=> 'Replace the main menu'
					);
				echo aq_field_select('clone', $block_id, $clone_arr, $clone); 
				?>
			
			</div>
			
			<div class="description third">
			
				<label for="<?php echo $this->get_field_id('back_color') ?>"><?php _e('Background color','sequoia'); ?>
				</label><br />
				<?php echo aq_field_color_picker('back_color', $this->block_id, $back_color ) ?>
				
				<p class="description">This color value overrides default and (if set) the body background color value (set in Theme options) </p>
				
								
				<label for="<?php echo $this->get_field_id('color') ?>">Fonts color
				</label><br />
				<?php echo aq_field_color_picker('color', $this->block_id, $color, $defaults['color']) ?>
			
			</div>
			
			<div class="description third last">
				<label for="<?php echo $this->get_field_id('menu_style') ?>"><?php _e('Menu style','sequoia'); ?></label>
				<br/>
				<?php
				$menu_style_arr = array(
					'style1'	=> 'Style 1 (default)',
					'style2'	=> 'Style 2'
					);
				echo aq_field_select('menu_style', $block_id, $menu_style_arr, $menu_style); 
				?>

				<div class="clearfix"></div>
			
				<label for="<?php echo $this->get_field_id('menu_align') ?>"><?php _e('Menu aligment','sequoia'); ?></label>
				<br/>
				<?php
				$menu_align_arr = array(
					'left'		=> 'Align left',
					'right'		=> 'Align right',
					'centered'	=> 'Centered'
					);
				echo aq_field_select('menu_align', $block_id, $menu_align_arr, $menu_align); 
				?>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('margin') ?>"><?php _e('Menu margin (in pixels)','sequoia'); ?></label><br>
				<?php echo aq_field_input('margin', $block_id, $margin, $size = 'min') ?> px
			</div>
			
			<hr>
			
			<h4>Image (logo) for Onepager Menu</h4>
			
			<hr>
			
			<?php //  New - logo image / title for Onapager menu block // ?>
			
			<div class="description third">
				
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
			
			<div class="description third">
				
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
				
				<label for="<?php echo $this->get_field_id('logo_width') ?>" class="half">Logo width
				<small>Use value AND units (px, em, rem or %), too.</small>
				</label><br/>
				<?php echo aq_field_input('logo_width', $block_id, $logo_width, $size = 'min' ) ?>
				
				<div class="clearfix"></div>
				
				<label for="<?php echo $this->get_field_id('logo_padding') ?>" class="half">Logo padding
				<small>Use value AND units (px, em, rem or %), too. Also you can add shorthand multiple values (20px 0 30px 10px)</small>
				</label><br />
				<?php echo aq_field_input('logo_padding', $block_id, $logo_padding );	?>
			
			
			</div>
			
			<div class="description third last">
				
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

			
			
			<?php
		}
		
		function icons_array() {
			
			include (get_template_directory() .'/inc/functions/animations-icons-arrays.php');
			return $icons_arr;

		}
		
		function create_menu_item($menu_item = array(), $count = 0) {
				
			?>
			<li id="<?php echo $this->get_field_id('menu_items') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">
				
				<div class="sortable-head cf">
					<div class="sortable-title">
						<strong><?php echo $menu_item['title'] ?></strong>
					</div>
					<div class="sortable-handle">
						<a href="#">Open / Close</a>
					</div>
				</div>
				
				<div class="sortable-body">
					<p class="tab-desc description">
						<label for="<?php echo $this->get_field_id('menu_items') ?>-<?php echo $count ?>-title">
							Menu item label<br/>
							<input type="text" id="<?php echo $this->get_field_id('menu_items') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('menu_items') ?>[<?php echo $count ?>][title]" value="<?php echo $menu_item['title'] ?>" />
						</label>
					</p>
					<p class="tab-desc description">
						<label for="<?php echo $this->get_field_id('menu_items') ?>-<?php echo $count ?>-content">
							Menu item anchor link<br/>
							
							<input type="text" id="<?php echo $this->get_field_id('menu_items') ?>-<?php echo $count ?>-content" class="input-full" name="<?php echo $this->get_field_name('menu_items') ?>[<?php echo $count ?>][content]" value="<?php echo $menu_item['content'] ?>" />
							
							
						</label>
						
						
					</p>
					
					<div class="clearfix"></div>
					
					<div class="description half">
						<label for="<?php echo $this->get_field_id('external') ?>-<?php echo $count ?>-external"><?php _e('External link (not anchored)','sequoia');?><br><small>For linking outside the one pager</small></label>
				
						<input type="checkbox" id="<?php echo $this->get_field_id('menu_items') ?>-<?php echo $count ?>-external" class="" name="<?php echo $this->get_field_name('menu_items') ?>[<?php echo $count ?>][external]"  <?php echo checked( 1, isset($menu_item['external']), false ); ?> value="1"/>
					</div>
					
					<div class="description half last">
					
						<label for="<?php echo $this->get_field_id('icons') ?>"><?php _e('Item icon','sequoia'); ?></label>
						<br/>
						<?php 
						
						$icons_arr = $this->icons_array();
						$options = is_array($icons_arr) ? $icons_arr : array();
						?>
						
						<select id="<?php echo $this->get_field_id('menu_items') ?>-<?php echo $count ?>-icon" name="<?php echo $this->get_field_name('menu_items') ?>[<?php echo $count ?>][icon]" class="select-icons">
						
						<?php 
						$output = '';
						foreach( $options as $key=>$value ) {
							$output .= '<option value="'.$key.'" '.selected( $menu_item['icon'], $key, false ).'>'.htmlspecialchars($value). '</option>';
						}
						echo $output;
						
						?>
						</select>
						
				
						<script>
						<?php $select = $this->get_field_id('menu_items').'-'. $count .'-icon'; ?>
						jQuery(document).ready(function() { 
							
							function format(state) {
								if (!state.id) return state.text; // optgroup
								return "<span class='" + state.id.toLowerCase() + "'></span> " + state.text;
							}
							
							
							jQuery("#<?php echo $select; ?>").select2({
								formatResult: format,
								formatSelection: format,
								escapeMarkup: function(m) { return m; }
							}); 
						
						});
						</script>
					
						
					</div>
					
					<p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
				</div>
				
			</li>
			
			
			<?php
		}
		
		function before_block($instance) {
	 		
			extract($instance);
	 		
	 		
	 		echo '<div id="aq-block-'.$template_id.'-'.$number.'" class="aq-block aq-block-'.$id_base.' aq_'.$size.'  clearfix">';
	 	}
		
		
		
		function block($instance) {
			extract($instance);
			
			//wp_enqueue_script('jquery-ui-tabs');
			
			echo '<style scoped>';
			if( $back_color || $color ) {
			
				echo '#'.$block_id.' {';
				echo $back_color	?	'background-color: rgba('.hex2rgb( $back_color ).', 0.9) !important;' : '';
				echo $color			?	'color: '. $color .';' : '';
				echo '}';

			}
			echo '</style>';
			$sticky_class = ( $sticky && !$clone ) ? ' sticky-block' : '' ;
			
			$output = '';
			
			$output .= '<div id="'.$block_id.'" class="aq_block_onepager_menu '.$sticky_class.' '.$css_classes.'">';
			
				$style = $margin ? 'style="margin:' . $margin .'px;"' : null;
				
				$to_header = $clone ? 'to-header' : null;
				
				if( $attach_id || $title ) {
					
					$output .= '<h1>';
					
					$output .= $link ? '<a href="'. esc_url($link) .'">' : null;
					
					if( $logo_width || $logo_padding ) {
				
						$output .= '<style scoped>';
						$output .= '#'.$block_id.' h1 .entry-image { ';
						
						$output .= $logo_width		?	'width:'.$logo_width.';' : '';
						$output .= $logo_padding	?	'padding:'.$logo_padding.';' : '';
					
						$output .= '}';
						
						$output .= '</style>';
					}
					
					if( $attach_id ) {
					
						$img_width = $img_height = '';
						$output .= as_get_unattached_image( $attach_id, $img_format, $img_width, $img_height );
						
					}elseif( !$attach_id && $title ) {
					
						$output .= esc_html( $title );
						
					}else{
						bloginfo( 'name' );
					}
					
					echo $link ? '</a>' : null;
						
					
					$output .= '</h1>';					
				}
				
				
				$output .= '<div class="menu-toggler"><a href="#" title="'.__('Toggle categories','sequoia').'" class="button tiny"><span class="fs" data-icon="&#xe05a;"></span></a></div>';
				
				$menu_align = 'align_' .$menu_align;
				
				$output .= '<ul class="taxonomy-menu onepager-menu '.$menu_style.' '.$menu_align.' '.$to_header. ' ' . $clone . '" id="onepager-'.$block_id.'" '. $style .'>';
				
				$i = 1;
				foreach( $menu_items as $menu_item ){
					
					if( isset($menu_item['external']) && $menu_item['external'] ) {
						$link = $menu_item['content'];
					}else{
						$link = '#' . sanitize_title( $menu_item['content'] );
					}
					
					$output .= '<li class="one-pager-item"><a href="'. $link . '"><div class="item-label"><span class="' . $menu_item['icon'] . '"></span> ' . $menu_item['title'] . '</div></a></li>';
					$i++;
				}
				
				$output .= '</ul>';
			
			$output .= '<div class="clearfix"></div></div>';

			$output .= '
			<script>
			jQuery(document).ready(function($){
				
				$(function (){
				
					var navEl = $("#onepager-'.$block_id.'");
					
					// IF IS TO BE MOVED TO SITE MENU / HEADER MENU:
					if( navEl.hasClass("to-header") ) {
					
						navEl.each( function () {
							
							var cloner = $(this).parent().clone();
							
							// REPLACE MAIN MENU
							if( navEl.hasClass("replace") ) {
								$( "#main-nav-wrapper" ).empty();
								cloner.prependTo( "#main-nav-wrapper" );
							}
							// PUT CLONE ABOVE MAIN MENU :
							if( navEl.hasClass("above") ) {
								cloner.prependTo( "#main-nav-wrapper" );
							}
							// PUT CLONE BELLOW THE MAIN MENU :
							if( navEl.hasClass("bellow") ) {
								cloner.appendTo( "#main-nav-wrapper" );
							}
							
							$(this).parent().parent().remove(); // REMOVE ORIGINAL
							
							navEl = cloner; // now the cloned nav element is navEl object
								
						});
					
					}
					
					// ADDITIONAL OFFSET (based on menu s height):
					
					var mainParent			= navEl.parent(),
						
						addHeight			= mainParent.outerHeight(true),
						
						classesZeroOffset	= navEl.hasClass("to-header") || !mainParent.hasClass("sticky-block") ? true : false,
						
						offset		=  classesZeroOffset ?  0 : addHeight;
					
					// INITIALIZE THE ONEPAGE NAV PLUGIN:
					navEl.onePageNav({
						currentClass : "current",
						changeHash : false,
						scrollSpeed : 750,
						scrollOffset : 100,
						offsetPlus: offset

					});
					
					var highest = -999;
					$("*").each(function() {
						var current = parseInt($(this).css("z-index"), 10);
						if(current && highest < current) highest = current;
					});

					navEl.closest(".aq-block-as_row_block").css("z-index",highest)
					
				});
				
			});
			</script>
			';
		
		echo $output;
			
		}
		
		/* AJAX add tab */
		function add_menu_item() {
			$nonce = $_POST['security'];
			if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');
			
			$count = isset($_POST['count']) ? absint($_POST['count']) : false;
			$this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';
			
			//default key/value for the tab
			$menu_item = array(
				'title'		=> 'New Menu Item',
				'content'	=> '',
				'external'	=> false,
				'icon'		=> ''
			);
			
			if($count) {
				$this->create_menu_item($menu_item, $count);
			} else {
				die(-1);
			}
			
			die();
		}
		
		function update($new_instance, $old_instance) {
			$new_instance = aq_recursive_sanitize($new_instance);
			return $new_instance;
		}
	}
}
