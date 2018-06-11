<?php
/*-----------------------------------------------------------------------------------*/
/* Title: Aquagraphite Options Framework
/* Author: Syamil MJ
/* Author URI: http://aquagraphite.com
/* License: GPL
/* Credits:	Thematic Options Panel http://wptheming.com/2010/11/thematic-options-panel-v2/
			KIA Thematic Options Panel https://github.com/helgatheviking/thematic-options-KIA
			Woo Themes http://woothemes.com/
			Option Tree http://wordpress.org/extend/plugins/option-tree/
/*-----------------------------------------------------------------------------------*/ 

/*-----------------------------------------------------------------------------------*/
/* Create the Options_Machine object - optionsframework_admin_init */
/*-----------------------------------------------------------------------------------*/
function optionsframework_admin_init() {
		// Rev up the Options Machine
		global $of_options, $options_machine;
		$options_machine = new Options_Machine($of_options);
		
		//if reset is pressed->replace options with defaults
	if ( isset($_REQUEST['page']) && $_REQUEST['page'] == 'optionsframework' ) {
		if (isset($_REQUEST['of_reset']) && 'reset' == $_REQUEST['of_reset']) {
			
			$nonce=$_POST['security'];

			if (!wp_verify_nonce($nonce, 'of_ajax_nonce') ) {
			
				header('Location: themes.php?page=optionsframework&reset=error');
				die('Security Check'); 
				
			} else {	
				
				$defaults = (array) $options_machine->Defaults;
				update_option(OPTIONS,$defaults);
				generate_options_css($defaults); //generate static css file
				
				header('Location: themes.php?page=optionsframework&reset=true');
				die($options_machine->Defaults);
			} 
		}
	}
}
add_action('admin_init','optionsframework_admin_init');

/*-----------------------------------------------------------------------------------*/
/* Options Framework Admin Interface - optionsframework_add_admin */
/*-----------------------------------------------------------------------------------*/

function optionsframework_add_admin() {
	
	$of_page = add_theme_page(THEMENAME, 'Theme Options', 'edit_theme_options', 'optionsframework','optionsframework_options_page'); // Default

	// Add framework functionaily to the head individually
	add_action("admin_print_scripts-$of_page", 'of_load_only');
	add_action("admin_print_styles-$of_page",'of_style_only');
} 

add_action('admin_menu', 'optionsframework_add_admin');


/*-----------------------------------------------------------------------------------*/
/* Build the Options Page - optionsframework_options_page */
/*-----------------------------------------------------------------------------------*/

function optionsframework_options_page(){
global $options_machine;
	/*
	//for debugging
	$of_sequoia = get_option(OPTIONS);
	print_r($of_sequoia);
	*/
?>

<div class="wrap" id="of_container">
  <div id="of-popup-save" class="of-save-popup">
	<div class="of-save-save">Options Updated</div>
  </div>
  <div id="of-popup-reset" class="of-save-popup">
	<div class="of-save-reset">Options Reset</div>
  </div>
   <div id="of-popup-fail" class="of-save-popup">
	<div class="of-save-fail">Error!</div>
  </div>

  <form id="of_form" method="post" action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ) ?>" enctype="multipart/form-data" >
	<div id="header">
	  <div class="logo">
		<h2><strong><?php bloginfo( 'name' ); ?></strong> <small>Options</small></h2>
	  </div>
	  <div id="js-warning">Warning- This options panel will not work properly without javascript!</div>
	  <div class="icon-option"> </div>
	  <div class="clear"></div>
	</div>

	<div id="info_bar"> 
	<a><div id="expand_options" class="expand">Expand</div></a>
	
	<a href="http://themeforest.net/user/aligatorstudio" target="_blank" class="as-links">Contact Us</a> | <a href="<?php echo get_template_directory_uri() ?>/documentation" target="_blank" class="as-links">Documentation</a>
	
	<img style="display:none" src="<?php echo esc_url(ADMIN_DIR); ?>images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
	<input type="hidden" id="security" name="security" value="<?php echo wp_create_nonce('of_ajax_nonce'); ?>" />	
	<button id ="of_save" type="button" class="button-primary"><?php _e('Save All Changes','sequoia');?></button>
	</div><!--.info_bar--> 

	<div id="main">
		
		<div id="of-nav">
			<ul>
				<?php echo wp_kses_decode_entities( $options_machine->Menu ); ?>
			</ul>
		</div>

		<div id="content">
			<?php echo wp_kses_decode_entities( $options_machine->Inputs ); ?> </div>
			<div class="clear"></div>
		</div>
	
	<div class="save_bar"> 
	<img style="display:none" src="<?php echo ADMIN_DIR; ?>images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
	<input type="hidden" id="security" name="security" value="<?php echo wp_create_nonce('of_ajax_nonce'); ?>" />
	<input type="hidden" name="of_reset" value="reset" />
	
	<button id ="of_save" type="submit" class="button-primary"><?php esc_html_e('Save All Changes','sequoia');?></button>
	<button id ="of_reset" type="submit" class="button submit-button reset-button" ><?php esc_html_e('Options Reset','sequoia');?></button>
	</div><!--.save_bar--> 
 
  </form>

 
<?php  if (!empty($update_message)) echo wp_kses_decode_entities($update_message); ?>
<div style="clear:both;"></div>

</div><!--wrap-->
<?php

}

/*-----------------------------------------------------------------------------------*/
/* Load required styles for Options Page - of_style_only */
/*-----------------------------------------------------------------------------------*/

function of_style_only(){
	wp_enqueue_style('admin-style', ADMIN_DIR . 'admin-style.css');
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_style('jquery-ui-custom-admin', ADMIN_DIR .'css/jquery-ui-custom.css');
}	

/*-----------------------------------------------------------------------------------*/
/* Load required javascripts for Options Page - of_load_only */
/*-----------------------------------------------------------------------------------*/

function of_load_only() {

	add_action('admin_head', 'of_admin_head');
	
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-resizable');
	wp_enqueue_script('jquery-ui-slider');
	wp_register_script('jquery-input-mask', ADMIN_DIR .'js/jquery.maskedinput-1.2.2.js', array( 'jquery' ));
	wp_enqueue_script('jquery-input-mask');
	wp_enqueue_script('tipsy', ADMIN_DIR .'js/jquery.tipsy.js', array( 'jquery' ));
	wp_enqueue_script('ajaxupload', ADMIN_DIR .'js/ajaxupload.js', array('jquery'));
	wp_enqueue_script('cookie', ADMIN_DIR . '/js/cookie.js', 'jquery');
	// Registers custom scripts for the Media Library AJAX uploader.
	wp_enqueue_script( 'wp-color-picker' );
		 
	if ( function_exists( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	} 
}


function of_admin_head() { 
			
	global $of_sequoia; ?>
		
	<script type="text/javascript" language="javascript">

	jQuery.noConflict();
	jQuery(document).ready(function($){
	
	//(un)fold options in a checkbox-group
  	jQuery('.fld').click(function() {
		var $fold='.f_'+this.id;
		$($fold).slideToggle('normal', "swing");
  	});
	
	//hide hidden section on page load.
	jQuery('#section-body_bg, #section-body_bg_custom, #section-body_bg_properties').hide();
	
	//delays until AjaxUpload is finished loading
	//fixes bug in Safari and Mac Chrome
	if (typeof AjaxUpload != 'function') { 
			return ++counter < 6 && window.setTimeout(init, counter * 500);
	}
	//hides warning if js is enabled			
	$('#js-warning').hide();
	
	//Tabify Options			
	$('.group').hide();
	
	// Display last current tab	
	if ($.cookie("of_current_opt") === null) {
		$('.group:first').fadeIn();	
		$('#of-nav li:first').addClass('current');
	} else {
	
		var hooks = <?php
		$hooks = of_get_header_classes_array();
		echo json_encode($hooks);		
		?>;
		
		$.each(hooks, function(key, value) { 
		
			if ($.cookie("of_current_opt") == '#of-option-'+ value) {
				$('.group#of-option-' + value).fadeIn();
				$('#of-nav li.' + value).addClass('current');
			}
			
		});
	
	}
				
	//Current Menu Class
	$('#of-nav li a').click(function(evt){
	// event.preventDefault();
				
		$('#of-nav li').removeClass('current');
		$(this).parent().addClass('current');
							
		var clicked_group = $(this).attr('href');
		
		$.cookie('of_current_opt', clicked_group, { expires: 7, path: '/' });
			
		$('.group').hide();
							
		$(clicked_group).fadeIn();
		return false;
						
	});

	//Expand Options 
	var flip = 0;
				
	$('#expand_options').click(function(){
		if(flip == 0){
			flip = 1;
			$('#of_container #of-nav').hide();
			$('#of_container #content').css('width','100%');
			$('#of_container .group').add('#of_container .group h2').show();
	
			$(this).removeClass('expand');
			$(this).addClass('close');
			$(this).text('Close');
					
		} else {
			flip = 0;
			$('#of_container #of-nav').show();
			$('#of_container #content').css('width','75%');
			$('#of_container .group').add('#of_container .group h2').hide();
			$('#of_container .group:first').show();
			$('#of_container #of-nav li').removeClass('current');
			$('#of_container #of-nav li:first').addClass('current');
					
			$(this).removeClass('close');
			$(this).addClass('expand');
			$(this).text('Expand');
				
		}
			
	});



	// Reset Message Popup
	var reset = "<?php if(isset($_REQUEST['reset'])) echo wp_kses_decode_entities($_REQUEST['reset']); ?>";
				
	if ( reset.length ){
		if ( reset == 'true') {
			var message_popup = $('#of-popup-reset');
		} else {
			var message_popup = $('#of-popup-fail');
	}
		message_popup.fadeIn();
		window.setTimeout(function(){
		message_popup.fadeOut();						
		}, 2000);	
	}
	
	//Update Message popup
	$.fn.center = function () {
		this.animate({"top":( $(window).height() - this.height() - 200 ) / 2+$(window).scrollTop() + "px"},100);
		this.css("left", 250 );
		return this;
	}
		
			
	$('#of-popup-save').center();
	$('#of-popup-reset').center();
	$('#of-popup-fail').center();
			
	$(window).scroll(function() { 
		$('#of-popup-save').center();
		$('#of-popup-reset').center();
		$('#of-popup-fail').center();
	});
			

	//Masked Inputs (images as radio buttons)
	$('.of-radio-img-img').click(function(){
		$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
		$(this).addClass('of-radio-img-selected');
	});
	$('.of-radio-img-label').hide();
	$('.of-radio-img-img').show();
	$('.of-radio-img-radio').hide();
	
	//Masked Inputs (FONT ICONS as radio buttons)
	$('.of-radio-icon').click(function(){
		$(this).parent().parent().find('.glyph').removeClass('of-radio-img-selected');
		$(this).parent().addClass('of-radio-img-selected');
	});
	
	//Masked Inputs (background images as radio buttons)
	$('.of-radio-tile-img').click(function(){
		$(this).parent().parent().find('.of-radio-tile-img').removeClass('of-radio-tile-selected');
		$(this).addClass('of-radio-tile-selected');
	});
	$('.of-radio-tile-label').hide();
	$('.of-radio-tile-img').show();
	$('.of-radio-tile-radio').hide();
	
	// COLOR Picker			
	$('.of-color').each(function(){
		var Othis = this; //cache a copy of the this variable for use inside nested function
		var	preview = $(Othis).closest('.controls').find('.google_font_preview');
		
		$(Othis).wpColorPicker({
			change: function( event, ui ){
				preview.css('color',  ui.color.toString());
			}
		});
 
	}); //end color picker

	//AJAX Upload
	function of_image_upload() {
	$('.image_upload_button').each(function(){
			
	var clickedObject = $(this);
	var clickedID = $(this).attr('id');	
			
	var nonce = $('#security').val();
			
	new AjaxUpload(clickedID, {
		action: ajaxurl,
		name: clickedID, // File upload name
		data: { // Additional data to send
			action: 'of_ajax_post_action',
			type: 'upload',
			security: nonce,
			data: clickedID },
		autoSubmit: true, // Submit file after selection
		responseType: false,
		onChange: function(file, extension){},
		onSubmit: function(file, extension){
			clickedObject.text('Uploading'); // change button text, when user selects file	
			this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
			interval = window.setInterval(function(){
				var text = clickedObject.text();
				if (text.length < 13){	clickedObject.text(text + '.'); }
				else { clickedObject.text('Uploading'); } 
				}, 200);
		},
		onComplete: function(file, response) {
			window.clearInterval(interval);
			clickedObject.text('Upload Image');	
			this.enable(); // enable upload button
				
	
			// If nonce fails
			if(response==-1){
				var fail_popup = $('#of-popup-fail');
				fail_popup.fadeIn();
				window.setTimeout(function(){
				fail_popup.fadeOut();						
				}, 2000);
			}				
					
			// If there was an error
			else if(response.search('Upload Error') > -1){
				var buildReturn = '<span class="upload-error">' + response + '</span>';
				$(".upload-error").remove();
				clickedObject.parent().after(buildReturn);
				
				}
			else{
				var buildReturn = '<img class="hide of-option-image" id="image_'+clickedID+'" src="'+response+'" alt="" />';

				$(".upload-error").remove();
				$("#image_" + clickedID).remove();	
				clickedObject.parent().after(buildReturn);
				$('img#image_'+clickedID).fadeIn();
				clickedObject.next('span').fadeIn();
				clickedObject.parent().prev('input').val(response);
			}
		}
	});
			
	});
	
	}
	
	of_image_upload();
			
	//AJAX Remove (clear option value)
	$('.image_reset_button').live('click', function(){
	
		var clickedObject = $(this);
		var clickedID = $(this).attr('id');
		var theID = $(this).attr('title');	
				
		var nonce = $('#security').val();
	
		var data = {
			action: 'of_ajax_post_action',
			type: 'image_reset',
			security: nonce,
			data: theID
		};
					
		$.post(ajaxurl, data, function(response) {
						
			//check nonce
			if(response==-1){ //failed
							
				var fail_popup = $('#of-popup-fail');
				fail_popup.fadeIn();
				window.setTimeout(function(){
					fail_popup.fadeOut();						
				}, 2000);
			}
						
			else {
						
				var image_to_remove = $('#image_' + theID);
				var button_to_hide = $('#reset_' + theID);
				image_to_remove.fadeOut(500,function(){ $(this).remove(); });
				button_to_hide.fadeOut();
				clickedObject.parent().prev('input').val('');
			}
						
						
		});
					
	}); 

	/* Style Select */
	
	(function ($) {
	styleSelect = {
		init: function () {
		$('.select_wrapper').each(function () {
			$(this).prepend('<span>' + $(this).find('.select option:selected').text() + '</span>');
		});
		$('.select').live('change', function () {
			$(this).prev('span').replaceWith('<span>' + $(this).find('option:selected').text() + '</span>');
		});
		$('.select').bind($.browser.msie ? 'click' : 'change', function(event) {
			$(this).prev('span').replaceWith('<span>' + $(this).find('option:selected').text() + '</span>');
		}); 
		}
	};
	$(document).ready(function () {
		styleSelect.init()
	})
	})(jQuery);
	
	
	//----------------------------------------------------------------*/
	// Aquagraphite Slider MOD
	//----------------------------------------------------------------*/
	/* Slider Interface */	
	
		//Hide (Collapse) the toggle containers on load
		$(".slide_body").hide(); 
	
		//Switch the "Open" and "Close" state per click then slide up/down (depending on open/close state)
		$(".slide_edit_button").live( 'click', function(){
			$(this).parent().toggleClass("active").next().slideToggle("fast");
			return false; //Prevent the browser jump to the link anchor
		});	
		
		// Update slide title upon typing		
		function update_slider_title(e) {
			var element = e;
			if ( this.timer ) {
				clearTimeout( element.timer );
			}
			this.timer = setTimeout( function() {
				$(element).parent().prev().find('small').text( element.value );
			}, 100);
			return true;
		}
		
		$(document).on('keyup', '.of-slider-title',function(){
			update_slider_title(this);
		});
		
	
	/* Remove individual slide */
	
		$('.slide_delete_button').live('click', function(){
		// event.preventDefault();
		var agree = confirm("Are you sure you wish to delete this slide?");
			if (agree) {
				var $trash = $(this).parents('li');
				//$trash.slideUp('slow', function(){ $trash.remove(); }); //chrome + confirm bug made slideUp not working...
				$trash.remove();
				return false; //Prevent the browser jump to the link anchor
			} else {
			return false;
			}	
		});
	
	/* Add new slide */
	
	$(".slide_add_button").live('click', function(){		
		var slidesContainer = $(this).prev();
		var sliderId = slidesContainer.attr('id');
		var sliderInt = $('#'+sliderId).attr('rel');
		
		var numArr = $('#'+sliderId +' li').find('.order').map(function() { 
			var str = this.id; 
			str = str.replace(/\D/g,'');
			str = parseFloat(str);
			return str;			
		}).get();
		
		if(numArr == '') {
			var numArr = $.makeArray( 0 )
		}
		
		var maxNum = Math.max.apply(Math, numArr);

		var newNum = maxNum + 1;
		
		slidesContainer.append('<li><div class="slide_header"><small>Slide ' + newNum + '</small><input type="hidden" class="slide of-input order" name="' + sliderId + '[' + newNum + '][order]" id="' + sliderId + '_slide_order-' + newNum + '" value="' + newNum + '"><a class="slide_edit_button" href="#">Edit</a></div><div class="slide_body" style="display: none; "><label>Title</label><input class="slide of-input of-slider-title" name="' + sliderId + '[' + newNum + '][title]" id="' + sliderId + '_' + newNum + '_slide_title" value=""><label>Image URL</label><input class="slide of-input" name="' + sliderId + '[' + newNum + '][url]" id="' + sliderId + '_' + newNum + '_slide_url" value=""><div class="upload_button_div"><span class="button media_upload_button" id="' + sliderId + '_' + newNum + '" rel="'+sliderInt+'">Upload</span><span class="button mlu_remove_button hide" id="reset_' + sliderId + '_' + newNum + '" title="' + sliderId + '_' + newNum + '">Remove</span></div><div class="screenshot"></div><label>Link URL (optional)</label><input class="slide of-input" name="' + sliderId + '[' + newNum + '][link]" id="' + sliderId + '_' + newNum + '_slide_link" value=""><label>Description (optional)</label><textarea class="slide of-input" name="' + sliderId + '[' + newNum + '][description]" id="' + sliderId + '_' + newNum + '_slide_description" cols="8" rows="8"></textarea><a class="slide_delete_button" href="#">Delete</a><div class="clear"></div></div></li>');
		of_image_upload(); // re-initialise upload image..
		return false; //prevent jumps, as always..
	});	
	
	// Sort Slides
	jQuery('.slider').find('ul').each( function() {
		var id = jQuery(this).attr('id');
		$('#'+ id).sortable({
			placeholder: "placeholder",
			opacity: 0.6
		});	
	});
		
	//----------------------------------------------------------------*/
	// Aligator Studio Icons MOD
	//----------------------------------------------------------------*/
	/*The Interface */	
	
		//Hide (Collapse) the toggle containers on load
		$(".icons_body").hide(); 
	
		//Switch the "Open" and "Close" state per click then slide up/down (depending on open/close state)
		$(".icon_item_edit_button").live( 'click', function(){
			$(this).parent().toggleClass("active").next().slideToggle("fast");
			return false; //Prevent the browser jump to the link anchor
		});	
		
		// Update icon title upon typing		
		function update_icon_item_title(e) {
			var element = e;
			if ( this.timer ) {
				clearTimeout( element.timer );
			}
			this.timer = setTimeout( function() {
				$(element).parent().prev().find('small').text( element.value );
			}, 100);
			return true;
		}
		
		$(document).on('keyup','.of-icon-title' , function(){
			update_icon_item_title(this);
		});
		
	
	/* Remove individual icon */
	
		$('.icon_item_delete_button').live('click', function(){
		// event.preventDefault();
		var agree = confirm("Are you sure you wish to delete this item?");
			if (agree) {
				var $trash = $(this).parents('li');
				//$trash.slideUp('slow', function(){ $trash.remove(); }); //chrome + confirm bug made slideUp not working...
				$trash.remove();
				return false; //Prevent the browser jump to the link anchor
			} else {
			return false;
			}	
		});
	
	/* Add new icon */
	
	$(document).on('click','.icon_add_button', function(){		
		var iconsContainer = $(this).prev();
		var iconId = iconsContainer.attr('id');
		var iconInt = $('#'+iconId).attr('rel');
		
		var numArr = $('#'+iconId +' li').find('.order').map(function() { 
			var str = this.id; 
			str = str.replace(/\D/g,'');
			str = parseFloat(str);
			return str;			
		}).get();
		
		if(numArr == '') {
			var numArr = $.makeArray( 0 )
		}
		
		var maxIcNum = Math.max.apply(Math, numArr);

		var newIconNum = maxIcNum + 1;
		
		var toAppend = '<li><div class="slide_header"><small>Item ' + newIconNum + '</small><input type="hidden" class="slide of-input order" name="' + iconId + '[' + newIconNum + '][order]" id="' + iconId + '_slide_order-' + newIconNum + '" value="' + newIconNum + '"><a class="icon_item_edit_button" href="#">Edit</a></div><div class="icons_body" style="display: none; "><label>Title</label><input class="slide of-input of-icon-title" name="' + iconId + '[' + newIconNum + '][title]" id="' + iconId + '_' + newIconNum + '_slide_title" value="">';
		
		toAppend += '<label>Toggle animation</label>';
		toAppend += '<input type="checkbox" class="checkbox of-input" name="' + iconId + '[' + newIconNum + '][toggle]" id="' + iconId + '_' + newIconNum + '_toggle" value="1" checked />';
		
		toAppend += '<label>Select icon</label><select id="' + iconId + '_' + newIconNum + '-icon" name="' + iconId + '[' + newIconNum + '][icon]" class="select-icons">';
		
		<?php
		$icons_array = Options_Machine::icons_array();
		$icons_options = array();
		foreach( $icons_array as $key=>$label ) {
			$icons_options[] = '<option id="'.$key.'" value="'.$key.'">'.htmlspecialchars($label). '</option>';
		}
		?>
		toAppend += '<?php echo implode('',$icons_options); ?>';
		
		toAppend += '</select><br>';
		
		toAppend += '<label>Link URL (optional)</label><input class="slide of-input" name="' + iconId + '[' + newIconNum + '][link]" id="' + iconId + '_' + newIconNum + '_slide_link" value=""><a class="icon_item_delete_button" href="#">Delete</a><div class="clear"></div></div></li>';
		
		iconsContainer.append( toAppend );
		
		function format(state) {
			if (!state.id) return state.text;
			return "<span class=\'" + state.id.toLowerCase() + "\'></span> " + state.text;
			}
		
		
		$('#' + iconId + '_' + newIconNum + '-icon').select2({
			formatResult: format,
			formatSelection: format,
			escapeMarkup: function(m) { return m; }
		});
		
		return false; //prevent jumps, as always..
	});	
	
	// Sort items
	jQuery('.slider').find('ul').each( function() {
		var id = jQuery(this).attr('id');
		$('#'+ id).sortable({
			placeholder: "placeholder",
			opacity: 0.6
		});	
	});
	
	
	
	/*----------------------------------------------------------------*/
	/*	Aquagraphite Sorter MOD
	/*----------------------------------------------------------------*/
	jQuery('.sorter').each( function() {
	
		var id = jQuery(this).attr('id');
		$('#'+ id).find('ul').sortable({
			items: 'li',
			placeholder: "placeholder",
			autoHide: true,
			connectWith: '.sortlist_' + id,
			opacity: 0.6,
			update: function() {
				$(this).find('.position').each( function() {
				
					var listID = $(this).parent().attr('id');
					var parentID = $(this).parent().parent().attr('id');
					parentID = parentID.replace(id + '_', '')
					var optionID = $(this).parent().parent().parent().attr('id');
					$(this).prop("name", optionID + '[' + parentID + '][' + listID + ']');
					
				});
			}
		});
		/**/
		$('#'+ id).find('ul.resizable').find('li').resizable({
			containment: "parent",
			handles: " e",
			grid: [1, 50],
			autoHide: true,
			start: function () {
				var perc_txt = $(this).find('.perc')
				perc_txt.fadeToggle(20);				
			},
			resize: function () {
				var container_width = $(this).parent().width();
				var liWidth = $(this).width();
				var percentage = Math.floor((liWidth/container_width) * 100);
				var perc_txt = $(this).find('.perc');
				perc_txt.html(percentage + '%');
				
			},
			stop: function () {

				var container_width = $(this).parent().width();
				var liWidth = $(this).width();
				var percentage =  Math.floor((liWidth/container_width) * 100);
				var perc_txt = $(this).find('.perc');
				perc_txt.delay(1000).fadeToggle(500);
				
				$(this).find('.position').each(
					function () {
						var this_id = $(this).attr('id');
						$(this).val( this_id + '|' + percentage );
					}
				);

			}
		});
	});
	
	/*----------------------------------------------------------------*/
	/*	Aquagraphite Backup & Restore MOD
	/*----------------------------------------------------------------*/
	//backup button
	$('#of_backup_button').live('click', function(){
	
		var answer = confirm("<?php _e( 'Click OK to backup your current saved options.', 'sequoia' ); ?>");
		
		if (answer){
	
			var clickedObject = $(this);
			var clickedID = $(this).attr('id');
					
			var nonce = $('#security').val();
		
			var data = {
				action: 'of_ajax_post_action',
				type: 'backup_options',
				security: nonce
			};
						
			$.post(ajaxurl, data, function(response) {
							
				//check nonce
				if(response==-1){ //failed
								
					var fail_popup = $('#of-popup-fail');
					fail_popup.fadeIn();
					window.setTimeout(function(){
						fail_popup.fadeOut();
					}, 2000);
				}
							
				else {
							
					var success_popup = $('#of-popup-save');
					success_popup.fadeIn();
					window.setTimeout(function(){
						location.reload();
					}, 1000);
				}

			});
			
		}
		
	return false;

	}); 
	
	//restore button
	$('#of_restore_button').live('click', function(){
	
		var answer = confirm("<?php _e('Warning: All of your current options will be replaced with the data from your last backup! Proceed?','sequoia');?>")
		
		if (answer){
	
			var clickedObject = $(this);
			var clickedID = $(this).attr('id');
					
			var nonce = $('#security').val();
		
			var data = {
				action: 'of_ajax_post_action',
				type: 'restore_options',
				security: nonce
			};
						
			$.post(ajaxurl, data, function(response) {
			
				//check nonce
				if(response==-1){ //failed
								
					var fail_popup = $('#of-popup-fail');
					fail_popup.fadeIn();
					window.setTimeout(function(){
						fail_popup.fadeOut();
					}, 2000);
				}
							
				else {
							
					var success_popup = $('#of-popup-save');
					success_popup.fadeIn();
					window.setTimeout(function(){
						location.reload();
					}, 1000);
				}	

			});
	
		}
	
	return false;

	});
	
	/**	Ajax Transfer (Import/Export) Option */
	$('#of_import_button').live('click', function(){
	
		var answer = confirm("Click OK to import options.")
		
		if (answer){
	
			var clickedObject = $(this);
			var clickedID = $(this).attr('id');
					
			var nonce = $('#security').val();
			
			var import_data = $('#export_data').val();
		
			var data = {
				action: 'of_ajax_post_action',
				type: 'import_options',
				security: nonce,
				data: import_data
			};
			
			$.post(ajaxurl, data, function(response) {
				var fail_popup = $('#of-popup-fail');
				var success_popup = $('#of-popup-save');
				
				//check nonce
				if(response==-1){ //failed
					fail_popup.fadeIn();
					window.setTimeout(function(){
						fail_popup.fadeOut();
					}, 2000);
				}		
				else 
				{
					success_popup.fadeIn();
					window.setTimeout(function(){
						location.reload();
					}, 1000);
				}

			});
			
		}
		
	return false;
					
	});
	
	
	
	/* save everything */
	$('#of_save, input.nag_input').live('click',function() {
			
		var nonce = $('#security').val();

		$('.ajax-loading-img').fadeIn();

		var serializedReturn = $('#of_form :input[name][name!="security"][name!="of_reset"]').serialize();

		var data = {
			<?php if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'optionsframework'){ ?>
			type: 'save',
			<?php } ?>

			action: 'of_ajax_post_action',
			security: nonce,
			data: serializedReturn
		};
					
		$.post(ajaxurl, data, function(response) {
			var success = $('#of-popup-save');
			var fail = $('#of-popup-fail');
			var loading = $('.ajax-loading-img');
			loading.fadeOut();  
						
			if (response==1) {
				success.fadeIn();
			} else { 
				fail.fadeIn();
			}
						
			window.setTimeout(function(){
				success.fadeOut(); 
				fail.fadeOut();				
			}, 2000);
		});
			
	return false; 
					
	});   
			
	//confirm reset			
	$('#of_reset').click(function() {
		var answer = confirm("<?php _e('Click OK to reset. All settings will be lost!','sequoia');?>")
		if (answer){ 	return true; } else { return false; }
});
			
	//custom js for checkbox hidden values	
	jQuery('#background_image').click(function() {
  		jQuery('#section-body_bg, #section-body_bg_custom, #section-body_bg_properties').fadeToggle(400);
	});
	
	if (jQuery('#background_image:checked').val() !== undefined) {
		jQuery('#section-body_bg, #section-body_bg_custom, #section-body_bg_properties').show();
	}
	

	
	/**	Tipsy @since v1.3 */
	if (jQuery().tipsy) {
		$('.typography-size, .typography-height, .typography-weight, .typography-face, .typography-style, .of-typography-color, .google-font-family, .google-weight, .google-size').tipsy({
			fade: true,
			gravity: 's',
			opacity: 0.7,
		});
		$('.explain').tipsy({
			fade:true,
			html:true,
			delayOut: 1500,
			gravity:'ne'
		});
	}
	
	
	/**
	  * JQuery UI Slider function
	  * Dependencies 	 : jquery, jquery-ui-slider
	  * Feature added by : Smartik - http://smartik.ws/
	  * Date 			 : 03.17.2013
	  */
	jQuery('.smof_sliderui').each(function() {
		
		var obj   = jQuery(this);
		var sId   = "#" + obj.data('id');
		var val   = parseInt(obj.data('val'));
		var min   = parseInt(obj.data('min'));
		var max   = parseInt(obj.data('max'));
		var step  = parseInt(obj.data('step'));
		
		//slider init
		obj.slider({
			value: val,
			min: min,
			max: max,
			step: step,
			slide: function( event, ui ) {
				jQuery(sId).val( ui.value );
			}
		});
		
	});
	
	
	/**
	  * Switch
	  * Dependencies 	 : jquery
	  * Feature added by : Smartik - http://smartik.ws/
	  * Date 			 : 03.17.2013
	  */
	jQuery(".cb-enable").click(function(){
		var parent = $(this).parents('.switch-options');
		jQuery('.cb-disable',parent).removeClass('selected');
		jQuery(this).addClass('selected');
		jQuery('.main_checkbox',parent).attr('checked', true);
		
		//fold/unfold related options
		var obj = jQuery(this);
		var $fold='.f_'+obj.data('id');
		jQuery($fold).slideDown('normal', "swing");
	});
	jQuery(".cb-disable").click(function(){
		var parent = $(this).parents('.switch-options');
		jQuery('.cb-enable',parent).removeClass('selected');
		jQuery(this).addClass('selected');
		jQuery('.main_checkbox',parent).attr('checked', false);
		
		//fold/unfold related options
		var obj = jQuery(this);
		var $fold='.f_'+obj.data('id');
		jQuery($fold).slideUp('normal', "swing");
	});
	//disable text select(for modern chrome, safari and firefox is done via CSS)
	if (($.browser.msie && $.browser.version < 10) || $.browser.opera) { 
		$('.cb-enable span, .cb-disable span').find().attr('unselectable', 'on');
	}
	
	
	/**
	  * Radio toggle switch
	  * Dependencies 	 : jquery
	  * Feature added by : Alen Sirola
	  * Date 			 : 17.04.2013
	*/
	jQuery(".of-radio").each(function(){
		
		if( jQuery(this).is(':checked') )  {
			jQuery('.'+ jQuery(this).attr('name') ).css('display','none');
			jQuery('.f_'+ jQuery(this).val() ).css('display','block');	
		}
		
	});
	jQuery(".of-radio").click(function(){
	
		jQuery('.'+ jQuery(this).attr('name') ).each(function () {
			jQuery(this).slideUp(500);
		});
		jQuery('.f_'+ jQuery(this).val() ).each(function() {
			jQuery(this).slideDown('slow');
		});
		
	});
	
	jQuery(".of-radio-img-img").each(function(){
		
		var _this = jQuery(this),
			input = _this.parent().find('input');
		
		if( _this.hasClass('of-radio-img-selected') )  {
			jQuery('.'+ input.attr('name') ).css('display','none');
			jQuery('.f_'+ input.val() ).css('display','block');	
		}
		
	});
	jQuery(".of-radio-img-img").click(function(){
	
		var _this = jQuery(this),
			input = _this.parent().find('input');
		
		jQuery('.'+ input.attr('name') ).each(function () {
			jQuery(this).slideUp(500);
		});
		jQuery('.f_'+ input.val() ).each(function() {
			jQuery(this).slideDown('slow');
		});
		
	});

	
	
	/**
	  * Google Fonts
	  * Dependencies 	 : google.com, jquery
	  * Feature added by : Smartik - http://smartik.ws/
	  * Date 			 : 03.17.2013
	*/
	
	function GoogleFontSelect( slctr, mainID ){
		
		var _selected = $(slctr).val();//get current value - selected and saved
		var _linkclass = 'style_link_'+ mainID;
		var _previewer = mainID +'_ggf_previewer';
		
		var _fontSelParent = $(slctr).parent().parent();
		var _weight = _fontSelParent.find('.google-weight').find('.google_weight_select').val();
		var _color = _fontSelParent.find('.of-typography-color').val(); 
		
		if( _selected ){ //if var exists and isset
			
			//Check if selected is not equal with "Select a font" and execute the script.
			if ( _selected !== 'none' && _selected !== 'Select a font' ) {
				
				//remove other elements crested in <head>
				$( '.'+ _linkclass ).remove();
				
				//replace spaces with "+" sign
				var the_font = _selected.replace(/\s+/g, '+');
				
				//add reference to google font family
				$('head').append('<link href="http://fonts.googleapis.com/css?family='+ the_font +':300,400,600,700,800,400italic,700italic" rel="stylesheet" type="text/css" class="'+ _linkclass +'">');
				
				//show in the preview box the font
				$('.'+ _previewer ).css('font-family', _selected +', sans-serif' );
				// change font weight in preview
				$('.'+ _previewer ).css('font-weight', _weight  );
				// change font color in preview
				$('.'+ _previewer ).css('color', _color  );
				
			}else{
				
				//if selected is not a font remove style "font-family" at preview box
				$('.'+ _previewer ).css('font-family', '' );
				
			}
		}
	}
	
	//init for each element
	jQuery( '.google_font_select' ).each(function(){ 
		var mainID = jQuery(this).attr('id');
		GoogleFontSelect( this, mainID );
	});
	//init when value is changed
	jQuery( '.google_font_select' ).change(function(){ 
		var mainID = jQuery(this).attr('id');
		GoogleFontSelect( this, mainID );
	});

	//init when value is changed
	jQuery( '.google_weight_select' ).change(function(){ 
		var mainSelector = jQuery(this).parent().parent().find('.google-font-family').find('.google_font_select');
		var mainID = mainSelector.attr('id');
		GoogleFontSelect( mainSelector, mainID );
	});
	
	
/**
	  * Media Uploader
	  * Dependencies 	 : jquery, wp media uploader
	  * Feature added by : Smartik - http://smartik.ws/
	  * Date 			 : 05.28.2013
	  */
	function optionsframework_add_file(event, selector) {
	
		var upload = $(".uploaded-file"), frame;
		var $el = $(this);

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.open();
			return;
		}

		// Create the media frame.
		frame = wp.media({
			// Set the title of the modal.
			title: $el.data('choose'),

			// Customize the submit button.
			button: {
				// Set the text of the button.
				text: $el.data('update'),
				// Tell the button not to close the modal, since we're
				// going to refresh the page when the image is selected.
				close: false
			}
		});

		// When an image is selected, run a callback.
		frame.on( 'select', function() {
			// Grab the selected attachment.
			var attachment = frame.state().get('selection').first();
			frame.close();
			selector.find('.upload').val(attachment.attributes.url);
			if ( attachment.attributes.type == 'image' ) {
				selector.find('.screenshot').empty().hide().append('<img class="of-option-image" src="' + attachment.attributes.url + '">').slideDown('fast');
			}
			selector.find('.media_upload_button').unbind();
			selector.find('.remove-image').show().removeClass('hide');//show "Remove" button
			selector.find('.of-background-properties').slideDown();
			optionsframework_file_bindings();
		});

		// Finally, open the modal.
		frame.open();
	}
	
	function optionsframework_remove_file(selector) {
		selector.find('.remove-image').hide().addClass('hide');//hide "Remove" button
		selector.find('.upload').val('');
		selector.find('.of-background-properties').hide();
		selector.find('.screenshot').slideUp();
		selector.find('.remove-file').unbind();
		// We don't display the upload button if .upload-notice is present
		// This means the user doesn't have the WordPress 3.5 Media Library Support
		if ( $('.section-upload .upload-notice').length > 0 ) {
			$('.media_upload_button').remove();
		}
		optionsframework_file_bindings();
	}
	
	function optionsframework_file_bindings() {
		$('.remove-image, .remove-file').on('click', function() {
			optionsframework_remove_file( $(this).parents('.section-upload, .section-media, .slide_body') );
		});
		
		$('.media_upload_button').unbind('click').click( function( event ) {
			optionsframework_add_file(event, $(this).parents('.section-upload, .section-media, .slide_body'));
		});
	}
	
	optionsframework_file_bindings();	
	
}); //end doc ready
</script>
<?php }

/*-----------------------------------------------------------------------------------*/
/* Ajax Save Action - of_ajax_callback */
/*-----------------------------------------------------------------------------------*/

add_action('wp_ajax_of_ajax_post_action', 'of_ajax_callback');

function of_ajax_callback() {
	
	global $options_machine, $of_options;

	$nonce=$_POST['security'];
	
	if (! wp_verify_nonce($nonce, 'of_ajax_nonce') ) die('-1'); 
			
	//get options array from db
	$all = get_option(OPTIONS);
		
	$save_type = $_POST['type'];
	
	//Uploads
	if($save_type == 'upload'){
		
		$clickedID = $_POST['data']; // Acts as the name
		$filename = $_FILES[$clickedID];
	   	$filename['name'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', $filename['name']); 
		
		$override['test_form'] = false;
		$override['action'] = 'wp_handle_upload';	
		$uploaded_file = wp_handle_upload($filename,$override);
		 
			$upload_tracking[] = $clickedID;
				
			//update $options array w/ image URL			  
			$upload_image = $all; //preserve current data
			
			$upload_image[$clickedID] = $uploaded_file['url'];
			
			update_option(OPTIONS, $upload_image ) ;
		
				
		 if(!empty($uploaded_file['error'])) {echo 'Upload Error: ' . esc_html($uploaded_file['error']); }	
		 else { echo esc_url( $uploaded_file['url'] ); } // Is the Response
		 
	}
	elseif($save_type == 'image_reset'){
			
			$id = $_POST['data']; // Acts as the name
			
			$delete_image = $all; //preserve rest of data
			$delete_image[$id] = ''; //update array key with empty value	 
			update_option(OPTIONS, $delete_image ) ;
	
	}
	elseif($save_type == 'backup_options'){
			
		$backup = $all;
		$backup['backup_log'] = date('r');
		
		update_option('backups', $backup ) ;
			
		die('1'); 
	}
	elseif($save_type == 'restore_options'){
			
		$of_sequoia = get_option('backups');
		
		update_option(OPTIONS, $of_sequoia);
		
		die('1'); 
	}
	elseif($save_type == 'import_options'){

		$of_sequoia = unserialize( urldecode( $_POST['data'] ) );
		
		update_option(OPTIONS,$of_sequoia);
		generate_options_css($of_sequoia); //generate static css file
		
		die('1'); 
	}
	elseif ($save_type == 'save') {
		
		parse_str(stripslashes($_POST['data']), $of_sequoia);
		unset($of_sequoia['security']);
		unset($of_sequoia['of_save']);
   
		update_option(OPTIONS, $of_sequoia);
		generate_options_css($of_sequoia); //generate static css file
		
		die('1'); 
		
	} elseif ($save_type == 'reset') {
		update_option(OPTIONS,$options_machine->Defaults);
		
		die(1); //options reset
		
	}

	die();

}


/*-----------------------------------------------------------------------------------*/
/* Class that Generates The Options Within the Panel - optionsframework_machine */
/*-----------------------------------------------------------------------------------*/

class Options_Machine {

	function __construct( $options ) {
		
		$return = $this->optionsframework_machine( $options );
		
		$this->Inputs = $return[0];
		$this->Menu = $return[1];
		$this->Defaults = $return[2];
		
	}

	/** 
	 * Sanitize option
	 *
	 * Sanitize & returns default values if don't exist
	 * 
	 * Notes:
	 * For further uses, you can check for the $value['type'] and performs
	 * more speficic sanitization on the option
	 * The ultimate objective of this function is to prevent the "undefined index"
	 * errors some authors are having due to malformed options array
	 */
	static function sanitize_option( $value ) {
		$defaults = array(
			"name" => '',
			"desc" => '',
			"id"   => '',
			"std"  => '',
			"mod"  => '',
			"type" => '',
		);

		$value = wp_parse_args( $value, $defaults );

		return $value;

	}
	
	/*-----------------------------------------------------------------------------------*/
	/* Generates The Options Within the Panel - optionsframework_machine */
	/*-----------------------------------------------------------------------------------*/

	public static function optionsframework_machine( $options ) {

		global $smof_details;
		
		$of_sequoia = get_option( OPTIONS );
		
		$defaults = array();
		$counter  = 0;
		$menu     = '';
		$output   = '';
		
		foreach ( $options as $value ) {
		
			$counter++;
			$val = '';
			
			// sanitize option
			if ( $value['type'] != "heading" )
				$value = self::sanitize_option($value);
			
			//create array of defaults		
			if ( $value['type'] == 'multicheck' ){
				if ( is_array( $value['std'] ) ){
					foreach ( $value['std'] as $i=>$key ){
						$defaults[ $value['id'] ][ $key ] = true;
					}
				} else {
						$defaults[ $value['id'] ][ $value['std'] ] = true;
				}
			} else {
				if ( isset( $value['id'] ) && isset( $value['std'] ) ) {
					$defaults[$value['id']] = $value['std'];
				}
			}
			
			
			if(!empty($of_sequoia) || !empty($data)){
			
				if (array_key_exists('id', $value) && !isset($of_sequoia[$value['id']])) {
					$of_sequoia[$value['id']] = $value['std'];
					if ($value['type'] == "checkbox" && $value['std'] == 0) {
						$of_sequoia[$value['id']] = 0;
					} else {
						$update_data = true;
					}
				}
				if (array_key_exists('id', $value) && !isset($smof_details[$value['id']])) {
					$smof_details[$value['id']] = $of_sequoia[$value['id']];
				}
			
			
			//Start Heading of each option ///////////////////////
			if ( $value['type'] != "heading" ) {
				$class = ''; if(isset( $value['class'] )) { $class = $value['class']; }
				
				//hide items in checkbox group
				$fold='';
				if (array_key_exists("fold",$value)) {
					if (isset($of_sequoia[$value['fold']]) && $of_sequoia[$value['fold']]) {
						$fold="f_".$value['fold']." ";
					} else {
						$fold="f_".$value['fold']." temphide ";
					}
					$fold_id = str_replace(' temphide ','', $fold);
				}
				$unfold = isset($value['unfold']) ? ' '.$value['unfold'] : $unfold = ' ';
				
				$output .= '<div id="section-'.($fold ? $fold_id : $value['id']).'" class="'.$fold.'section section-'.$value['type'].' '. $class .''.$unfold.'">'."\n";
				if($value['name']) $output .= '<h3 class="heading">'. $value['name'] .'</h3>'."\n";
				$output .= '<div class="option">'."\n" . '<div class="controls">'."\n";

			}
			//End Heading ///////////////////////


			switch ( $value['type'] ) {
			////////////////////////////////////////
			case 'text':
				
				if(!isset($value['multi'])) {
				
					$t_value = '';
					$t_value = stripslashes($of_sequoia[$value['id']]);
					
					$mini ='';
					if(!isset($value['mod'])) $value['mod'] = '';
					if($value['mod'] == 'mini') { $mini = 'mini';}
					
					$output .= '<input class="of-input '.$mini.'" name="'.$value['id'].'" id="'. $value['id'] .'" type="'. $value['type'] .'" value="'. $t_value .'" />';
				
				}else{
					
					$saved_values = $of_sequoia[$value['id']];
					$std_values = $value['std'];
					
					if ( $saved_values ) {
						$fields_array = $saved_values; 
					}else{
						$fields_array = $std_values;
					};
					
					//if (!isset($saved_values[$key])) { $saved_values[$key] = ''; }		

					foreach ( $fields_array as $key => $option ) {
						
						$name_id = $value['id'] . '[' . $key . ']';
						
						$output .= '<div class="multi_txt_holder">';
						$output .= '<label for="'. $key .'" >'. $key .'</label>';
						$output .= '<input type="'. $value['type'] .'" name="' . $name_id .'" id="'. $name_id .'" value="' . $option . '" class="mini" />';
						$output .= '</div>';
						
						
					}//end foreach
					
				}
				
			break;
			////////////////////////////////////////
			case 'select':
				$mini ='';
				if(!isset($value['mod'])) $value['mod'] = '';
				if($value['mod'] == 'mini') { $mini = 'mini';}
				$output .= '<div class="select_wrapper ' . $mini . '">';
				$output .= '<select class="select of-input" name="'.$value['id'].'" id="'. $value['id'] .'">';

				
				foreach ($value['options'] as $select_ID => $option) {			
					$output .= '<option id="' . $select_ID . '" value="'.$select_ID.'" ' . selected($of_sequoia[$value['id']], $select_ID, false) . ' />'.
					$option.'</option>';	 
				 } 

				 
				$output .= '</select></div>';
			break;	
			////////////////////////////////////////	
			case 'select_category':
				$mini ='';
				if(!isset($value['mod'])) $value['mod'] = '';
				if($value['mod'] == 'mini') { $mini = 'mini';}
				$output .= '<div class="select_wrapper ' . $mini . '">';
				$output .= '<select class="select of-input" name="'.$value['id'].'" id="'. $value['id'] .'">';
				
				foreach ($value['options'] as $select_ID => $option) {			
					$output .= '<option id="' . $select_ID . '" value="'.$option[1].'" ' . selected($of_sequoia[$value['id']], $option[1], false) . ' />'.
					$option[0].'</option>';	 
				};

				$output .= '</select></div>';
			break;
			////////////////////////////////////////
			case 'html':
				
				$t = $value['desc_html'];
				$output .= '<div class="wrap">' .$t . '</div>';
			
			break;
			////////////////////////////////////////
			case 'textarea':	
				$cols = '8';
				$ta_value = '';
				
				if(isset($value['options'])){
						$ta_options = $value['options'];
						if(isset($ta_options['cols'])){
						$cols = $ta_options['cols'];
						} 
					}
					
					$ta_value = stripslashes($of_sequoia[$value['id']]);			
					$output .= '<textarea class="of-input" name="'.$value['id'].'" id="'. $value['id'] .'" cols="'. $cols .'" rows="8">'.$ta_value.'</textarea>';		
			break;
			////////////////////////////////////////
			case "radio":
				 
				 if( isset( $of_sequoia[$value['id']] ) ) {
					$saved_values = $of_sequoia[$value['id']];
				 }
				 
				 foreach($value['options'] as $option=>$name) {
					$output .= '<input class="of-input of-radio" name="'.$value['id'].'" type="radio" value="'.$option.'" ' . checked($saved_values, $option, false) . ' /><label class="of-radio-label">'.$name.'</label><br/>';				
				}	 
			break;
			////////////////////////////////////////
			case 'checkbox':
				if (!isset($of_sequoia[$value['id']])) {
					$of_sequoia[$value['id']] = 0;
				}
				
				$fold = '';
				if (array_key_exists("folds",$value)) $fold="fld ";
				
				$output .= '<input type="hidden" class="'.$fold.'checkbox aq-input" name="'.$value['id'].'" id="'. $value['id'] .'" value="0"/>';
				$output .= '<input type="checkbox" class="'.$fold.'checkbox of-input" name="'.$value['id'].'" id="'. $value['id'] .'" value="1" '. checked($of_sequoia[$value['id']], 1, false) .' />';
			break;
			////////////////////////////////////////
			case 'multicheck': 			
				
				if( isset($of_sequoia[$value['id']]) ) {
					$multi_stored = $of_sequoia[$value['id']];
				}		
				
				if ( isset($value['options']) ) {
				
					foreach ($value['options'] as $key => $option) {

						if (!isset($multi_stored[$key])) { $multi_stored[$key] = ''; }
						$of_key_string = $value['id'] . '_' . $key;
						
						$output .= '<input type="checkbox" class="checkbox of-input" name="'.$value['id'].'['.$key.']'.'" id="'. $of_key_string .'" value="1" '. checked($multi_stored[$key], 1, false) .' /><label for="'. $of_key_string .'">'. $option .'</label><br />';
						
					}
					$output .= '<input type="hidden" class="checkbox of-input" name="styles_overrides[placebo]" id="placebo" value="1"  />';
				
				}
				
			break;
			////////////////////////////////////////
			/* 
			case 'upload':
				if(!isset($value['mod'])) $value['mod'] = '';
				$output .= Options_Machine::optionsframework_uploader_function($value['id'],$value['std'],$value['mod']);			
			break;
			////////////////////////////////////////
			case 'media':
				$_id = strip_tags( strtolower($value['id']) );
				$int = '';
				$int = optionsframework_mlu_get_silentpost( $_id );
				if(!isset($value['mod'])) $value['mod'] = '';
				$output .= Options_Machine::optionsframework_media_uploader_function( $value['id'], $value['std'], $int, $value['mod'] ); // New AJAX Uploader using Media Library			
			break;		
			*/
			
			// Uploader 3.5
			case "upload":
			case "media":

				if(!isset($value['mod'])) $value['mod'] = '';
				
				$u_val = '';
				if($of_sequoia[$value['id']]){
					$u_val = stripslashes($of_sequoia[$value['id']]);
				}

				$output .= Options_Machine::optionsframework_media_uploader_function($value['id'],$u_val, $value['mod']);
				
			break;
			
			
			
			
			////////////////////////////////////////
			case 'color':	
			
				$output .= '<input class="of-color" name="'.$value['id'].'" id="'. $value['id'] .'" type="text" value="'. $of_sequoia[$value['id']] .'" />';
				
			break;   
			////////////////////////////////////////
			
			case 'typography':
			
				$typography_stored = $of_sequoia[$value['id']];
			
				
				/* Font Face */
				
				if(isset($typography_stored['face']) ) {
				
					$output .= '<div class="select_wrapper typography-face" original-title="Font family">';
					$output .= '<select class="of-typography of-typography-face select" name="'.$value['id'].'[face]" id="'. $value['id'].'_face">';
					
					$faces = array(	'arial'			=>'Arial',
									'verdana'		=>'Verdana, Geneva',
									'Trebuchet MS'	=>'Trebuchet',
									'georgia'		=>'Georgia',
									'times'			=>'Times New Roman',
									'tahoma'		=>'Tahoma, Geneva',
									'helvetica'		=>'Helvetica'
									);			
					foreach ($faces as $i=>$face) {
						$output .= '<option value="'. $i .'" ' . selected($typography_stored['face'], $i, false) . '>'. $face .'</option>';
					}			
									
					$output .= '</select></div>';
				
				}
				
				/* Font Size */
				
				if(isset($typography_stored['size'])) {
				
					$output .= '<div class="select_wrapper typography-size small-select" original-title="Font size">';
					$output .= '<select class="of-typography of-typography-size select" name="'.$value['id'].'[size]" id="'. $value['id'].'_size">';
					
						$output .= '<option value=""></option>';
						$start = 10;
						$increment = 1;

						for ( $i = 0; $i < 40; $i++ ) {
							$test = $start + $increment * $i . 'px';
							$output .= '<option value="'. $test .'" ' . selected($typography_stored['size'], $test, false) . '>'. $test .'</option>'; 
							
							//printf("%f ", start + increment * i);
						}
			
					$output .= '</select></div>';
				
				}		
				
				/* Font Weight */
				
				if(isset($typography_stored['weight'])) {
				
					$output .= '<div class="select_wrapper typography-weight small-select" original-title="Font weight">';
					$output .= '<select class="of-typography of-typography-weight select" name="'.$value['id'].'[weight]" id="'. $value['id'].'_weight">';
					$weights =  array(
						''		=> '',
						'100'	=> '100',
						'200'	=> '200',
						'300'	=> '300',
						'400'	=> '400',
						'500'	=> '500',
						'600'	=> '600',
						'700'	=> '700',
						'800'	=> '800',
						'900'	=> '900'
					);
									
					foreach ($weights as $i=>$weight){
					
						$output .= '<option value="'. $i .'" ' . selected($typography_stored['weight'], $i, false) . '>'. $weight .'</option>';		
					}
					$output .= '</select></div>';
				
				}
				
				/* Font Style */
				
				if(isset($typography_stored['style'])) {
				
					$output .= '<div class="select_wrapper typography-style small-select" original-title="Font style">';
					$output .= '<select class="of-typography of-typography-style select" name="'.$value['id'].'[style]" id="'. $value['id'].'_style">';
					$styles = array('normal'=>'Normal',
									'italic'=>'Italic'
									);
									
					foreach ($styles as $i=>$style){
					
						$output .= '<option value="'. $i .'" ' . selected($typography_stored['style'], $i, false) . '>'. $style .'</option>';		
					}
					$output .= '</select></div>';
				
				}
				
				/* Font Color */

				if(isset($typography_stored['color'])) {
				
					$output .= '<input class="of-color of-typography of-typography-color" name="'.$value['id'].'[color]" id="'. $value['id'] .'_color" type="text" value="'. $typography_stored['color'] .'"  original-title="Font color"/>';
				
				}			

				
				/* Line Height */
				
				if(isset($typography_stored['height'])) {
				
					$output .= '<div class="select_wrapper typography-height small-select" original-title="Line height">';
					$output .= '<select class="of-typography of-typography-height select" name="'.$value['id'].'[height]" id="'. $value['id'].'_height">';
						/*
						for ($i = 20; $i < 38; $i++){ 
							$test = $i.'px';
							$output .= '<option value="'. $i .'px" ' . selected($typography_stored['height'], $test, false) . '>'. $i .'px</option>'; 
							}
						*/
						
						$output .= '<option value=""></option>';

						$start = 0.8;
						$increment = 0.05;
						for ( $i = 0; $i < 57; $i++ ) {
							$test = $start + $increment * $i . 'em';
							$output .= '<option value="'. $test .'" ' . selected($typography_stored['height'], $test, false) . '>'. $test .'</option>'; 
							
							//printf("%f ", start + increment * i);
						}
			
					$output .= '</select></div>';
				
				}

				
				/* Opacity (NOT FOR FONTS) */
				
				if(isset($typography_stored['opacity'])) {
				
					$output .= '<div class="select_wrapper typography-opacity small-select">';
					$output .= '<select class="of-typography of-typography-opacity select" name="'.$value['id'].'[opacity]" id="'. $value['id'].'_opacity">';
						
						$output .= '<option value=""></option>';

						$start = 0;
						$increment = 0.1;
						for ( $i = 0; $i < 11; $i++ ) {
							$test = $start + $increment * $i ;
							$output .= '<option value="'. $test .'" ' . selected($typography_stored['opacity'], $test, false) . '>'. $test .'</option>'; 
							
							//printf("%f ", start + increment * i);
						}
			
					$output .= '</select></div>';
				
				}
				
			break; // case typography
			////////////////////////////////////////
			// google font field
			case 'select_google_font':
		
				$google_font_stored = $of_sequoia[$value['id']];
				
				
				if(isset($value['preview']['text'])){
					$g_text = $value['preview']['text'];
				} else {
					$g_text = '0123456789 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmnopqrstuvwxyz';
				}
				
				if(isset($value['preview']['size'])) {
					$g_size = 'style="font-size: '. $value['preview']['size'] .';"';
				} else { 
					$g_size = '';
				}
				
				$output .= '<small style="clear:both;display:block;">PREVIEW:</small><p class="'.$value['id'].'_ggf_previewer google_font_preview" '. $g_size .'>'. $g_text .'</p>';			
				
				/* USER ADDED GOOGLE FONTS */
				$added_fonts = isset($of_sequoia["added_google_fonts"])  ? $of_sequoia["added_google_fonts"] : '';
				
				//if( preg_match('/^[a-zA-Z0-9, ]+$/',$added_fonts ) ) {}else{ $add_fonts_array = array(); }
				
				// SANITIZATION:
				$added_fonts = preg_replace('/[^a-zA-Z0-9, ]/','',$added_fonts ) ;// remove all but numbers, letters, spaces and comma
				$added_fonts = preg_replace('/\s+/', ' ', $added_fonts);// remove multiple spaces
				$added_fonts = str_replace(", ",",", $added_fonts ); // remove space after comma
				
				$add_fonts_array_simple = explode(",",$added_fonts );
				$add_fonts_array = array_combine(  $add_fonts_array_simple,  $add_fonts_array_simple );				
				
				if(isset($google_font_stored['face']) ) {
				
					$output .= '<div class="select_wrapper google-font-family" original-title="Font family">';
					$output .= '<select class="of-input google_font_select select" name="'.$value['id'].'[face]" id="'. $value['id'].'">';
					
					$faces = apply_filters("as_google_fonts", $add_fonts_array);
					foreach ($faces as $i=>$face) {
						$output .= '<option value="'. $i .'" ' . selected($google_font_stored['face'], $i, false) . '>'. $face .'</option>';
					}			
									
					$output .= '</select></div>';
				
				}

				

				
				if(isset($google_font_stored['weight'])) {
					$output .= '<div class="select_wrapper google-weight small-select" original-title="Google font weight">';
					$output .= '<select class="of-input google_weight_select select" name="'.$value['id'].'[weight]" id="'. $value['id'].'_weight">';
					$weights = array(
						''		=> '',
						'100'	=> '100',
						'200'	=> '200',
						'300'	=> '300',
						'400'	=> '400',
						'500'	=> '500',
						'600'	=> '600',
						'700'	=> '700',
						'800'	=> '800',
						'900'	=> '900'
					);

					foreach ($weights as $i=>$weight){
						$output .= '<option value="'. $i .'" ' . selected($google_font_stored['weight'], $i, false) . '>'. $weight .'</option>';		
					}
					$output .= '</select></div>';
				}
				
				if(isset($google_font_stored['size'])) {
					$output .= '<div class="select_wrapper google-size small-select" original-title="Google font size">';
					$output .= '<select class="of-input google_size_select select" name="'.$value['id'].'[size]" id="'. $value['id'].'_size">';
						$output .= '<option value=""></option>';
						$start = 12;
						$increment = 1;

						for ( $i = 0; $i < 38; $i++ ) {
							$test = $start + $increment * $i . 'px';
							$output .= '<option value="'. $test .'" ' . selected($google_font_stored['size'], $test, false) . '>'. $test .'</option>'; 
						}
					$output .= '</select></div>';
				}	
				
				if(isset($google_font_stored['color'])) {
				
					$output .= '<input class="of-color of-typography of-typography-color google-color" name="'.$value['id'].'[color]" id="'. $value['id'] .'_color" type="text" value="'. $google_font_stored['color'] .'"  original-title="Google font color"/>';
				
				}	
				
				
			break;
			////////////////////////////////////////
			case 'border':
			
				$border_stored = $of_sequoia[$value['id']];	
				
				/********** Border Width **********/
				if( isset($border_stored['width']) ) {
				
					$output .= '<div class="select_wrapper border-width">';
					$output .= '<select class="of-border of-border-width select" name="'.$value['id'].'[width]" id="'. $value['id'].'_width">';
						for ($i = 0; $i < 21; $i++){ 
						$output .= '<option value="'. $i .'" ' . selected($border_stored['width'], $i, false) . '>'. $i .'</option>';				 }
					$output .= '</select></div>';
					
				}
				
				/********** Border Style **********/
				if( isset($border_stored['style']) ) {
				
					$output .= '<div class="select_wrapper border-style">';
					$output .= '<select class="of-border of-border-style select" name="'.$value['id'].'[style]" id="'. $value['id'].'_style">';
					
					$styles = array('none'		=>'None',
									'solid'		=>'Solid',
									'dashed'	=>'Dashed',
									'dotted'	=>'Dotted',
									'double'	=>'Double'
									);
									
					foreach ($styles as $key => $val){
						
						$output .= '<option value="'. $key .'" ' . selected($border_stored['style'], $key, false) . '>'. $val .'</option>';		
					}
					$output .= '</select></div>';
					
				}
				
				/********** Border Color **********/
				if( isset($border_stored['color']) ) {
				
					$output .= '<input class="of-color of-border of-border-color" name="'.$value['id'].'[color]" id="'. $value['id'] .'_color" type="text" value="'. $border_stored['color'] .'" />';
				
				}
				
				/********** Border Decoration **********/
				if( isset($border_stored['decoration']) ) {
				
					$output .= '<div id="' . $value['id'] . '_decor" class="of-border-decoration">';
					$i = 0;
					
					$decor_array = array(
						'deco-icon-squares_2'			=>'deco-icon-squares_2',
						'deco-icon-squares'				=>'deco-icon-squares',
						'deco-icon-square_lines_vert'	=>'deco-icon-square_lines_vert',
						'deco-icon-square_lines_horiz'	=>'deco-icon-square_lines_horiz',
						'deco-icon-square_double'		=>'deco-icon-square_double',
						'deco-icon-square_dot'			=>'deco-icon-square_dot',
						'deco-icon-square_cross'		=>'deco-icon-square_cross',
						'deco-icon-square'				=>'deco-icon-square',
						'deco-icon-romb_raster'			=>'deco-icon-romb_raster',
						'deco-icon-romb_line'			=>'deco-icon-romb_line',
						'deco-icon-romb_filled'			=>'deco-icon-romb_filled',
						'deco-icon-romb_double'			=>'deco-icon-romb_double',
						'deco-icon-romb_dots'			=>'deco-icon-romb_dots',
						'deco-icon-romb_dot'			=>'deco-icon-romb_dot',
						'deco-icon-romb'				=>'deco-icon-romb',
						'deco-icon-ornament_13'			=>'deco-icon-ornament_13',
						'deco-icon-ornament_12'			=>'deco-icon-ornament_12',
						'deco-icon-ornament_11'			=>'deco-icon-ornament_11',
						'deco-icon-ornament_10'			=>'deco-icon-ornament_10',
						'deco-icon-ornament_9'			=>'deco-icon-ornament_9',
						'deco-icon-ornament_8'			=>'deco-icon-ornament_8',
						'deco-icon-ornament_7'			=>'deco-icon-ornament_7',
						'deco-icon-ornament_6'			=>'deco-icon-ornament_6',
						'deco-icon-ornament_5'			=>'deco-icon-ornament_5',
						'deco-icon-ornament_4'			=>'deco-icon-ornament_4',
						'deco-icon-ornament_3'			=>'deco-icon-ornament_3',
						'deco-icon-ornament_2'			=>'deco-icon-ornament_2',
						'deco-icon-ornament_1'			=>'deco-icon-ornament_1',
						'deco-icon-circles_2'			=>'deco-icon-circles_2',
						'deco-icon-circles_1'			=>'deco-icon-circles_1',
						'deco-icon-circle_empty'		=>'deco-icon-circle_empty',
						'deco-icon-circle_double'		=>'deco-icon-circle_double',
						'deco-icon-circle_dot_smaller'	=>'deco-icon-circle_dot_smaller',
						'deco-icon-circle_dot'			=>'deco-icon-circle_dot',
						'deco-icon-circle_cross'		=>'deco-icon-circle_cross',
						'deco-icon-arrow_double_up'		=>'deco-icon-arrow_double_up',
						'deco-icon-arrow_double_down'	=>'deco-icon-arrow_double_down',
						'deco-icon-arrow_dot_up'		=>'deco-icon-arrow_dot_up',
						'deco-icon-arrow_dot_down'		=>'deco-icon-arrow_dot_down',
						'deco-icon-arrow_dot_both'		=>'deco-icon-arrow_dot_both',
						''								=>''
					);
					
					$output .= '<h4>Decoration:</h4>';
					foreach ( $decor_array as $key => $option ) { 
					
						$i++;
						$checked = '';
						$select_css = '';
						if( checked( $border_stored['decoration'], $key, false ) != NULL ) {
						
							$checked = checked( $border_stored['decoration'], $key, false );
							$select_css = 'of-radio-img-selected';  
							
						}
						$output .= '<span class="glyph '. $select_css .'">';
						$output .= '<input type="radio" id="'. $value['id'] .'_decoration_'.$i.'" class="checkbox of-radio-img-radio" value="'.$key.'" name="'. $value['id'].'[decoration] " '. $checked .' />';
						
						$output .= '<span class="'.$option.' of-radio-icon"	onClick="document.getElementById(\''. $value['id'] .'_decoration_'.$i.'\').checked = true;"></span>';
						$output .= '</span>';
					}
					$output .= '</div>';
				
				}
				
			break;
			
			// Images
			case 'images':
			
				$i = 0;
				
				$select_value = $of_sequoia[$value['id']];
				
				foreach ($value['options'] as $key => $option) { 
					
					$i++;
		
					$checked = '';
					$selected = '';
					if(NULL!=checked($select_value, $key, false)) {
						$checked = checked($select_value, $key, false);
						$selected = 'of-radio-img-selected';  
					}
					$output .= '<span>';
					$output .= '<input type="radio" id="of-radio-img-' . $value['id'] . $i . '" class="checkbox of-radio-img-radio" value="'.$key.'" name="'.$value['id'].'" '.$checked.' />';
					$output .= '<div class="of-radio-img-label">'. $key .'</div>';
					$output .= '<img src="'.$option.'" alt="" class="of-radio-img-img '. $selected .'" onClick="document.getElementById(\'of-radio-img-'. $value['id'] . $i.'\').checked = true;" />';
					$output .= '</span>';				
				}
				
			break;
			
			// Info
			case "info":
				$info_text = $value['std'];
				$output .= '<div class="of-info">'.$info_text.'</div>';
			break;   


			// Heading
			case 'heading':
				if($counter >= 2){
				   $output .= '</div>'."\n";
				}
				$header_class = preg_replace("/[^a-zA-Z0-9-]/", "", strtolower($value['name']) );
				$jquery_click_hook = preg_replace("/[^a-zA-Z0-9-]/", "", strtolower($value['name']) );
				$jquery_click_hook = "of-option-" . $jquery_click_hook;
				$menu .= '<li class="'. $header_class .'"><a title="'.  $value['name'] .'" href="#'.  $jquery_click_hook  .'">'.  $value['name'] .'</a></li>';
				$output .= '<div class="group" id="'. $jquery_click_hook  .'"><h2>'.$value['name'].'</h2>'."\n";
			break;
			

			// Slider
			case 'slider':
				
				$output .= '<div class="slider"><ul id="'.$value['id'].'">';
				$slides = $of_sequoia[$value['id']];
				$count = count($slides);
				if ($count < 2) {
					$oldorder = 1;
					$order = 1;
					$output .= Options_Machine::optionsframework_slider_function($value['id'],$value['std'],$oldorder,$order);
				} else {
					$i = 0;
					foreach ($slides as $slide) {
						$oldorder = $slide['order'];
						$i++;
						$order = $i;
						$output .= Options_Machine::optionsframework_slider_function($value['id'],$value['std'],$oldorder,$order);
					}
				}			
				$output .= '</ul>';
				$output .= '<a href="#" class="button slide_add_button">Add New Slide</a></div>';
		
			break;
			
			////////////////////////////////////////
			case 'icons':
				
				$output .= '<div class="slider"><ul id="'.$value['id'].'">';
				$icons = $of_sequoia[$value['id']];
				$count = count($icons);
				if ($count < 2) {
					$oldorder = 1;
					$order = 1;
					$output .= Options_Machine::optionsframework_icons_function($value['id'],$value['std'],$oldorder,$order);
				} else {
					$i = 0;
					foreach ($icons as $icon) {
						$oldorder = $icon['order'];
						$i++;
						$order = $i;
						$output .= Options_Machine::optionsframework_icons_function($value['id'],$value['std'],$oldorder,$order);
					}
				}			
				$output .= '</ul>';
				$output .= '<a href="#" class="button icon_add_button">Add New Icon</a></div>';
				
			break;
			////////////////////////////////////////
			case 'sorter':
			
				$sortlists = $of_sequoia[$value['id']];
				
				$output .= '<div id="'.$value['id'].'" class="sorter">';
				
				if ($sortlists) {
				
					foreach ($sortlists as $group => $sortlist) {
					
						if( isset( $value['resizable'] )) {
							$resiz = $value['resizable'] ? ' resizable': '';
						}
						$output .= '<ul id="'.$value['id'].'_'.$group.'" class="sortlist_'.$value['id'].$resiz.'">';
						$output .= '<h3>'.$group.'</h3>';
						
						foreach ($sortlist as $key => $list) {
						
							$output .= '<input class="sorter-placebo" type="hidden" name="'.$value['id'].'['.$group.'][placebo]" value="placebo">';
								
							if ($key != "placebo") {
							
								$string_to_array = explode("|", $list );			
								$array_to_string = implode("|", $string_to_array );
								
								
								$output .= '<li id="'.$key.'" class="sortee" style="width:'.$string_to_array[1].'%">';
										
								$output .= '<input class="position" type="hidden" name="'.$value['id'].'['.$group.']['.$key.']" value="'. $array_to_string . '" id="'.$string_to_array[0].'">';
								

								$output .= '<small>' . $string_to_array[0] . '</small><div class="perc"></div>';
								$output .= '</li>';
								
							}
							
						}
						
						$output .= '</ul>';
					}
				}
				
				$output .= '</div>';
			break;	
			////////////////////////////////////////
			case 'tiles':
				
				$i = 0;
				
				if(isset( $of_sequoia[$value['id']] )):
				
					$select_value = '';
					$select_value = $of_sequoia[$value['id']];
				
				endif;
				
				foreach ($value['options'] as $key => $option) { 
					$i++;
		
					$checked = '';
					$selected = '';
					if(NULL!=checked($select_value, $option, false)) {
						$checked = checked($select_value, $option, false);
						$selected = 'of-radio-tile-selected';  
					}
					$output .= '<span>';
					$output .= '<input type="radio" id="of-radio-tile-' . $value['id'] . $i . '" class="checkbox of-radio-tile-radio" value="'.$option.'" name="'.$value['id'].'" '.$checked.' />';
					$output .= '<div class="of-radio-tile-img '. $selected .'" style="background: url('.$option.')" onClick="document.getElementById(\'of-radio-tile-'. $value['id'] . $i.'\').checked = true;"></div>';
					$output .= '</span>';				
				}
				
			break;
			// Background
			case 'background':

				$background = $of_sequoia[$value['id']];


				$output .= '<div class="' . esc_attr( $class ) . '"></div>';

				/******************** BACKGROUND Repeat***********************/
				
				if ( isset($background['repeat']) ) {
				
					$output .= '<h4>Background repeat</h4>';
					
					$output .= '<div class="select_wrapper background-repeat" original-title="Background repeat">';
					$output .= '<select class="of-background of-background-repeat select" name="'.$value['id'].'[repeat]" id="'.$value['id'].'[repeat]">';
					//$repeats = of_recognized_background_repeat();
					$repeats = array('repeat' => 'Repeat', 'no-repeat' => 'No repeat', 'repeat-x'=>'Repeat X', 'repeat-y'=> 'Repeat Y' );

					
					foreach ($repeats as $key => $repeat) {
						$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['repeat'], $key, false ) . '>'. esc_html( $repeat ) . '</option>';
					}
					$output .= '</select></div>';
				}

				/******************** BACKGROUND Position***********************/
				if ( isset($background['position']) ) {
				
					$output .= '<h4>Background position</h4>';
					
					$output .= '<div class="select_wrapper background-position" original-title="Background position">';
					$output .= '<select class="of-background of-background-position select" name="'.$value['id'].'[position]" id="'.$value['id'].'[position]">';
					//$positions = of_recognized_background_position();
					$positions = array('center' => 'Center', 'left' => 'Left' );

					foreach ($positions as $key=>$position) {
						$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['position'], $key, false ) . '>'. esc_html( $position ) . '</option>';
					}
					$output .= '</select></div>';
				
				}

				/******************** BACKGROUND  Attachment***********************/
				if ( isset($background['attachment']) ) {
				
					$output .= '<h4>Background attachment</h4>';
					$output .= '<div class="select_wrapper background-attachment" original-title="Background attachment">';
				
					$output .= '<select class="of-background of-background-attachment select" name="'.$value['id'].'[attachment]" id="'.$value['id'].'[attachment]">';
					//$attachments = of_recognized_background_attachment();
					$attachments = array('scroll' => 'Scroll', 'fixed' => 'Fixed', );

					foreach ($attachments as $key => $attachment) {
						$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['attachment'], $key, false ) . '>' . esc_html( $attachment ) . '</option>';
					}
					$output .= '</select></div>';
				}
				
				/******************** BACKGROUND  Size***********************/
				if ( isset($background['size']) ) {
					$output .= '<h4>Background size</h4>';
					
					$output .= '<div class="select_wrapper background-size" original-title="Background size">';
					
					$output .= '<select class="of-background of-background-size select" name="'.$value['id'].'[size]" id="'.$value['id'].'[size]">';
					//$sizes = of_recognized_background_size();
					$sizes = array(''=> '', '50%' => '50%', '100% 100%'=> '100%', 'cover' => 'Cover','contain' => 'Contain' );

					foreach ($sizes as $key => $size) {
						$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['size'], $key, false ) . '>' . esc_html( $size ) . '</option>';
					}
					$output .= '</select></div>';
				}
				
			break; 
			
			case 'backup':
			
				$instructions = $value['options'];
				$backup = get_option('backups');
				
				if(!isset($backup['backup_log'])) {
					$log = 'No backups yet';
				} else {
					$log = $backup['backup_log'];
				}
				
				$output .= '<div class="backup-box">';
				$output .= '<div class="instructions">'.$instructions."\n";
				$output .= '<p><strong>'. __('Last Backup : ','sequoia').'<span class="backup-log">'.$log.'</span></strong></p></div>'."\n";
				$output .= '<a href="#" id="of_backup_button" class="button" title="Backup Options">Backup Options</a>';
				$output .= '<a href="#" id="of_restore_button" class="button" title="Restore Options">Restore Options</a>';
				$output .= '</div>';
			
			break;
			
			//export or import data between different installs
			case 'transfer':
			
				$instructions = $value['desc'];
				$output .= '<textarea id="export_data" rows="8">'. urlencode( serialize( $of_sequoia ) ) .'</textarea>'."\n";
				$output .= '<a href="#" id="of_import_button" class="button" title="Restore Options">Import Options</a>';
			
			break;
			

			//JQuery UI Slider
			case 'sliderui':
				$s_val = $s_min = $s_max = $s_step = $s_edit = '';//no errors, please
				
				$s_val  = stripslashes($of_sequoia[$value['id']]);
				
				if(!isset($value['min'])){ $s_min  = '0'; }else{ $s_min = $value['min']; }
				if(!isset($value['max'])){ $s_max  = $s_min + 1; }else{ $s_max = $value['max']; }
				if(!isset($value['step'])){ $s_step  = '1'; }else{ $s_step = $value['step']; }
				
				if(!isset($value['edit'])){ 
					$s_edit  = ' readonly="readonly"'; 
				}
				else
				{
					$s_edit  = '';
				}
				
				if ($s_val == '') $s_val = $s_min;
				
				//values
				$s_data = 'data-id="'.$value['id'].'" data-val="'.$s_val.'" data-min="'.$s_min.'" data-max="'.$s_max.'" data-step="'.$s_step.'"';
				
				//html output
				$output .= '<input type="text" name="'.$value['id'].'" id="'.$value['id'].'" value="'. $s_val .'" class="mini" '. $s_edit .' />';
				$output .= '<div id="'.$value['id'].'-slider" class="smof_sliderui" style="margin-left: 7px;" '. $s_data .'></div>';
				
			break;
					
					
			//Switch option
			case 'switch':
				if (!isset($of_sequoia[$value['id']])) {
					$of_sequoia[$value['id']] = 0;
				}

				$fold = '';
				if (array_key_exists("folds",$value)) $fold="s_fld ";

				$cb_enabled = $cb_disabled = '';//no errors, please

				//Get selected
				if ($of_sequoia[$value['id']] == 1){
					$cb_enabled = ' selected';
					$cb_disabled = '';
				}else{
					$cb_enabled = '';
					$cb_disabled = ' selected';
				}

				//Label ON
				if(!isset($value['on'])){
					$on = "On";
				}else{
					$on = $value['on'];
				}

				//Label OFF
				if(!isset($value['off'])){
					$off = "Off";
				}else{
					$off = $value['off'];
				}

				$output .= '<p class="switch-options">';
				$output .= '<label class="'.$fold.'cb-enable'. $cb_enabled .'" data-id="'.$value['id'].'"><span>'. $on .'</span></label>';
				$output .= '<label class="'.$fold.'cb-disable'. $cb_disabled .'" data-id="'.$value['id'].'"><span>'. $off .'</span></label>';

				$output .= '<input type="hidden" class="'.$fold.'checkbox of-input" name="'.$value['id'].'" id="'. $value['id'] .'" value="0"/>';
				$output .= '<input type="checkbox" id="'.$value['id'].'" class="'.$fold.'checkbox of-input main_checkbox" name="'.$value['id'].'"  value="1" '. checked($of_sequoia[$value['id']], 1, false) .' />';

				$output .= '</p>';

			break;
			
			} 
			
				// if TYPE is an array, formatted into smaller inputs... ie smaller values
				if ( is_array($value['type'])) {
					foreach($value['type'] as $array){
					
							$id = $array['id']; 
							$std = $array['std'];
							$saved_std = get_option($id);
							if($saved_std != $std){$std = $saved_std;} 
							$meta = $array['meta'];
							
							if($array['type'] == 'text') { // Only text at this point
								 
								 $output .= '<input class="input-text-small of-input" name="'. $id .'" id="'. $id .'" type="text" value="'. $std .'" />';  
								 $output .= '<span class="meta-two">'.$meta.'</span>';
							}
						}
				}
				if ( $value['type'] != 'heading' ) { 
					if(!isset($value['desc']) || $value['desc'] == '' ){ $explain_value = ''; } else{ 
						$explain_value = '<div class="explain" original-title="'.$value['desc'].'"></div>'."\n"; 
					} 
					$output .= '</div>'.$explain_value."\n";
					$output .= '<div class="clear"> </div></div></div>'."\n";
				}
		   
			}
		
		}
		$output .= '</div>';

		return array( $output, $menu, $defaults );

	}


	/*-----------------------------------------------------------------------------------*/
	/* Aquagraphite Uploader - optionsframework_uploader_function */
	/*-----------------------------------------------------------------------------------*/

	public static function optionsframework_uploader_function($id,$std,$mod){

		$of_sequoia =get_option(OPTIONS);
		
		$uploader = '';
		$upload = $of_sequoia[$id];
		$hide = '';
		
		if ($mod == "min") {$hide ='hide';}
		
		if ( $upload != "") { 
			$val = $upload;
		}else{
			$val = '';
		}
		/* else {
			$val = $std;
		}*/
		
		$uploader .= '<input class="'.$hide.' upload of-input" name="'. $id .'" id="'. $id .'_upload" value="'. $val .'" />';	
		
		$uploader .= '<div class="upload_button_div"><span class="button image_upload_button" id="'.$id.'">'._('Upload', 'sequoia').'</span>';
		
		if(!empty($upload)) {$hide = '';} else { $hide = 'hide';}
		$uploader .= '<span class="button image_reset_button '. $hide.'" id="reset_'. $id .'" title="' . $id . '">Remove</span>';
		$uploader .='</div>' . "\n";
		$uploader .= '<div class="clear"></div>' . "\n";
		if(!empty($upload)){
			$uploader .= '<div class="screenshot">';
			$uploader .= '<a class="of-uploaded-image" href="'. $upload . '">';
			$uploader .= '<img class="of-option-image" id="image_'.$id.'" src="'.$upload.'" alt="" />';
			$uploader .= '</a>';
			$uploader .= '</div>';
			}
		$uploader .= '<div class="clear"></div>' . "\n"; 

	return $uploader;
	}

	/*-----------------------------------------------------------------------------------*/
	/* Aquagraphite Media Uploader - optionsframework_media_uploader_function */
	/*-----------------------------------------------------------------------------------*/
	public static function optionsframework_media_uploader_function($id,$std,$mod){

		$of_sequoia = get_option(OPTIONS);
		
		$uploader = '';
		$upload = $of_sequoia[$id];
		$hide = '';
		
		if ($mod == "min") {$hide ='hide';}
		
		if ( $upload != "") { $val = $upload; } else {$val = $std;}
		
		$uploader .= '<input class="'.$hide.' upload of-input" name="'. $id .'" id="'. $id .'_upload" value="'. $val .'" />';	
		
		//Upload controls DIV
		$uploader .= '<div class="upload_button_div">';
		//If the user has WP3.5+ show upload/remove button
		if ( function_exists( 'wp_enqueue_media' ) ) {
			$uploader .= '<span class="button media_upload_button" id="'.$id.'">Upload</span>';
			
			if(!empty($upload)) {$hide = '';} else { $hide = 'hide';}
			$uploader .= '<span class="button remove-image '. $hide.'" id="reset_'. $id .'" title="' . $id . '">Remove</span>';
		}
		else 
		{
			$output .= '<p class="upload-notice"><i>Upgrade your version of WordPress for full media support.</i></p>';
		}

		$uploader .='</div>' . "\n";

		//Preview
		$uploader .= '<div class="screenshot">';
		if(!empty($upload)){	
			$uploader .= '<a class="of-uploaded-image" href="'. $upload . '">';
			$uploader .= '<img class="of-option-image" id="image_'.$id.'" src="'.$upload.'" alt="" />';
			$uploader .= '</a>';			
			}
		$uploader .= '</div>';
		$uploader .= '<div class="clear"></div>' . "\n"; 
	
		return $uploader;
		
	}


	/*-----------------------------------------------------------------------------------*/
	/* Aquagraphite Slider - optionsframework_slider_function */
	/*-----------------------------------------------------------------------------------*/

	public static function optionsframework_slider_function($id,$std,$oldorder,$order){
		
	   $of_sequoia = get_option(OPTIONS);
		
		$slider = '';
		$slide = array();
		$slide = $of_sequoia[$id];
		
		if (isset($slide[$oldorder])) { $val = $slide[$oldorder]; } else {$val = $std;}
		
		//initialize all vars
		$slidevars = array('title','url','link','description');
		
		foreach ($slidevars as $slidevar) {
			if (!isset($val[$slidevar])) {
				$val[$slidevar] = '';
			}
		}
		
		//begin slider interface	
		if (!empty($val['title'])) {
			$slider .= '<li><div class="slide_header"><strong>'.stripslashes($val['title']).'</strong>';
		} else {
			$slider .= '<li><div class="slide_header"><strong>Slide '.$order.'</strong>';
		}
		
		$slider .= '<input type="hidden" class="slide of-input order" name="'. $id .'['.$order.'][order]" id="'. $id.'_'.$order .'_slide_order" value="'.$order.'" />';
	
		$slider .= '<a class="slide_edit_button" href="#">Edit</a></div>';
		
		$slider .= '<div class="slide_body">';
		
		$slider .= '<label>Title</label>';
		$slider .= '<input class="slide of-input of-slider-title" name="'. $id .'['.$order.'][title]" id="'. $id .'_'.$order .'_slide_title" value="'. stripslashes($val['title']) .'" />';
		
		$slider .= '<label>Image URL</label>';
		$slider .= '<input class="upload slide of-input" name="'. $id .'['.$order.'][url]" id="'. $id .'_'.$order .'_slide_url" value="'. $val['url'] .'" />';
		
		$slider .= '<div class="upload_button_div"><span class="button media_upload_button" id="'.$id.'_'.$order .'">Upload</span>';
		
		if(!empty($val['url'])) {$hide = '';} else { $hide = 'hide';}
		$slider .= '<span class="button remove-image '. $hide.'" id="reset_'. $id .'_'.$order .'" title="' . $id . '_'.$order .'">Remove</span>';
		$slider .='</div>' . "\n";
		$slider .= '<div class="screenshot">';
		if(!empty($val['url'])){
			
			$slider .= '<a class="of-uploaded-image" href="'. $val['url'] . '">';
			$slider .= '<img class="of-option-image" id="image_'.$id.'_'.$order .'" src="'.$val['url'].'" alt="" />';
			$slider .= '</a>';
			
			}
		$slider .= '</div>';	
		$slider .= '<label>Link URL (optional)</label>';
		$slider .= '<input class="slide of-input" name="'. $id .'['.$order.'][link]" id="'. $id .'_'.$order .'_slide_link" value="'. $val['link'] .'" />';
		
		$slider .= '<label>Description (optional)</label>';
		$slider .= '<textarea class="slide of-input" name="'. $id .'['.$order.'][description]" id="'. $id .'_'.$order .'_slide_description" cols="8" rows="8">'.stripslashes($val['description']).'</textarea>';
	
		$slider .= '<a class="slide_delete_button" href="#">Delete</a>';
		$slider .= '<div class="clear"></div>' . "\n";
	
		$slider .= '</div>';
		$slider .= '</li>';
	
		return $slider;
		
	}

	/*-----------------------------------------------------------------------------------*/
	/* AligatorStudio Icons - optionsframework_icons_function */
	/*-----------------------------------------------------------------------------------*/
	public static function icons_array() {
		include (get_template_directory() .'/inc/functions/animations-icons-arrays.php');
		//$icons_arr = ob_get_clean();
		return $icons_arr;
	}
	
	
	public static function optionsframework_icons_function($id,$std,$oldorder,$order){

		$of_sequoia = get_option(OPTIONS);
		
		$icons_holder = '';
		$slide = array();
		$slide = $of_sequoia[$id];
		
		if (isset($slide[$oldorder])) { $val = $slide[$oldorder]; } else {$val = $std;}
		
		//initialize all vars
		$slidevars = array('title','link','description','icon','toggle');
		
		foreach ($slidevars as $slidevar) {
			if (!isset($val[$slidevar])) {
				$val[$slidevar] = '';
			}
		}
		
		//begin slider interface	
		if (!empty($val['title'])) {
			$icons_holder .= '<li><div class="slide_header"><small>'.stripslashes($val['title']).'</small>';
		} else {
			$icons_holder .= '<li><div class="slide_header"><small>Item '.$order.'</small>';
		}
		
		$icons_holder .= '<input type="hidden" class="slide of-input order" name="'. $id .'['.$order.'][order]" id="'. $id.'_'.$order .'_slide_order" value="'.$order.'" />';

		$icons_holder .= '<a class="icon_item_edit_button" href="#">Edit</a></div>';
		
		$icons_holder .= '<div class="icons_body">';
		
		$icons_holder .= '<label>Label</label>';
		$icons_holder .= '<input class="slide of-input of-icon-title" name="'. $id .'['.$order.'][title]" id="'. $id .'_'.$order .'_slide_title" value="'. stripslashes($val['title']) .'" />';
		
		$icons_holder .= '<label>Toggle animation</label>';
		$icons_holder .= '<input type="checkbox" class="checkbox of-input" name="'. $id .'['.$order.'][toggle]" id="'. $id .'_'.$order .'_toggle" value="1" '. checked($val['toggle'], 1, false) .' />';
		
		// SELECT
		$icons_array = Options_Machine::icons_array();
		
		$icons_holder .= '<label>Select icon</label>';
		$icons_holder .= '<select id="'.$id.'_'.$order .'-icon" name="'. $id .'['.$order.'][icon]" class="select-icons">';

			//$icons_holder .= '<option id="'.$key.'-empty" value="'.$key.'-empty" '.selected( $val['icon'], $key, false ).'></option>';
			
			foreach( $icons_array as $key=>$label ) {
				$icons_holder .= '<option id="'.$key.'" value="'.$key.'" '.selected( $val['icon'], $key, false ).'>'.htmlspecialchars($label). '</option>';
			}

		$icons_holder .= '</select><br>';
	/* 	$icons_holder .= '<script>';
		
		$icons_holder .= 'jQuery(document).ready(function() { ';
		
		$icons_holder .= '	function format(state) {';
		$icons_holder .= '		if (!state.id) return state.text;';
		$icons_holder .= '		return "<span class=\'" + state.id.toLowerCase() + "\'></span> " + state.text;';
		$icons_holder .= '	}';
			
			
		$icons_holder .= '		jQuery("#'.$id.'_'.$order .'-icon").select2({';
		$icons_holder .= '		allowClear: true,';
		$icons_holder .= '		formatResult: format,';
		$icons_holder .= '		formatSelection: format,';
		$icons_holder .= '			escapeMarkup: function(m) { return m; }';
		$icons_holder .= '		}); ';
		$icons_holder .= '});';
		$icons_holder .= '</script>'; */
		// end SELECT
		
		
		
		$icons_holder .= '<label>Link URL (optional)</label>';
		$icons_holder .= '<input class="slide of-input" name="'. $id .'['.$order.'][link]" id="'. $id .'_'.$order .'_slide_link" value="'. $val['link'] .'" />';
		
		$icons_holder .= '<a class="icon_item_delete_button" href="#">Delete</a>';
		$icons_holder .= '<div class="clear"></div>' . "\n";

		$icons_holder .= '</div>';
		$icons_holder .= '</li>';

	return $icons_holder;

	}
	
	
/*-----------------------------------------------------------------------------------*/
/* End Class
/*-----------------------------------------------------------------------------------*/	
}	//end class