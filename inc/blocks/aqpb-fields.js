/**
 * AQPB Fields JS
 *
 * JS functionalities for some of the default fields
 */

jQuery.noConflict();

/** Fire up jQuery - let's dance! 
 */
jQuery(document).ready(function($){

	/** Colorpicker Field
	----------------------------------------------- */
	$.fn.aqpb_colorpicker = function() {
	//function aqpb_colorpicker() {
		this.each(function(){
			var $this	= $(this),
				
				sliderControls	= $this.closest('.slider-controls'); // slider-controls div
							
			$this.wpColorPicker({
				
					change : function(event, ui) {
					
					if( sliderControls.hasClass('slider-controls') ) {
					
						if( sliderControls.hasClass('icon-color') ) {
							sliderControls.parent().find('.preview-box').find('#preview-icon').css( 'color', ui.color.toString() );
							
						}else 					
						if( sliderControls.hasClass('icon-border-color') ) {
							sliderControls.parent().find('.preview-box').find('#preview-icon').css( 'border-color', ui.color.toString() );
						}else 					
						if( sliderControls.hasClass('icon-background-color') ) {
							sliderControls.parent().find('.preview-box').find('#preview-icon').css( 'background-color', ui.color.toString() ).attr('data-backcolor', ui.color.toString());
						}else
						if( sliderControls.hasClass('block-background-color') ) {
							sliderControls.parent().find('.preview-box').css( 'background-color', ui.color.toString() ).attr('data-backcolor', ui.color.toString());
						}
						
					}
					
				}		
			
			});
		
		}); // end each

	}// end function aqpb_colorpicker()

	$('#page-builder .input-color-picker').aqpb_colorpicker();

	$(document).on('sortstop','ul.blocks', function() {
		$(this).find('.input-color-picker').aqpb_colorpicker();
	});
	/*
	$( "ul.blocks" ).droppable({
      drop: function( event, ui ) {
        ui.draggable.find('.input-color-picker').aqpb_colorpicker();
      }
    });
	*/
	/** Media Uploader
	----------------------------------------------- */
	$(document).on('click', '.aq_upload_button', function(event) {
		var $clicked = $(this), frame,
			input_id = $clicked.prev().attr('id'),
			img_size = $clicked.prev().attr("data-size"),
			media_type = $clicked.attr('rel');
			
		event.preventDefault();
		
		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.open();
			return;
		}
		
		// Create the media frame.
		frame = wp.media.frames.aq_media_uploader = wp.media({
			// Set the media type
			library: {
				type: media_type
			},
			view: {
				
			}
		});
		
		// When an image is selected, run a callback.
		frame.on( 'select', function() {
			// Grab the selected attachment.
			var attachment = frame.state().get('selection').first();
			
			$('#' + input_id).val(attachment.attributes.id);
			
			if(media_type == 'image') $('#' + input_id).parent().parent().parent().find('.screenshot img.att-image').attr('src', attachment.attributes.sizes[img_size].url);
			
		});

		frame.open();
	
	});
	$(document).on('click', 'a.remove-media', function(event) {
		
		event.preventDefault();
		
		var imgDiv = $(this).parent().parent().find('.screenshot');
		var placeHolderImg = imgDiv.find('input.placeholder').val();
		
		imgDiv.find('img.att-image').attr('src', placeHolderImg );
		
		$(this).parent().parent().find('input.input-upload').val('');
		
	});
		

	/** Sortable Lists
	----------------------------------------------- */
	// AJAX Add New <list-item>
	function aq_sortable_list_add_item(action_id, items) {
		
		var blockID = items.attr('rel'),
			numArr = items.find('li').map(function(i, e){
				return $(e).attr("rel");
			});
				
		var maxNum = Math.max.apply(Math, numArr);
		if (maxNum < 1 ) { maxNum = 0};
		var newNum = maxNum + 1;
		
		var data = {
			action: 'aq_block_'+action_id+'_add_new',
			security: $('#aqpb-nonce').val(),
			count: newNum,
			block_id: blockID
		};
		
		$.post(ajaxurl, data, function(response) {
			var check = response.charAt(response.length - 1);
			
			//check nonce
			if(check == '-1') {
				alert('An unknown error has occurred');
			} else {
				items.append(response);
			}
						
		});
	};
	
	// Initialise sortable list fields
	function aq_sortable_list_init() {
		$('.aq-sortable-list').sortable({
			containment: "parent",
			placeholder: "ui-state-highlight",
			opacity: 0.6,
			cursor: 'move',
			revert: true
		});
	}
	aq_sortable_list_init();
	
	$('ul.blocks').bind('sortstop', function() {
		aq_sortable_list_init();
	});
	
	
	$(document).on('click', 'a.aq-sortable-add-new', function() {
		var action_id = $(this).attr('rel'),
			items = $(this).parent().children('ul.aq-sortable-list');
			
		aq_sortable_list_add_item(action_id, items);
		aq_sortable_list_init

		return false;
	});
	
	// Delete Sortable Item
	$(document).on('click', '.aq-sortable-list a.sortable-delete', function() {
		var $parent = $(this.parentNode.parentNode.parentNode);
		$parent.children('.block-tabs-tab-head').css('background', 'red');
		$parent.slideUp(function() {
			$(this).remove();
		}).fadeOut('fast');
		return false;
	});
	
	// Open/Close Sortable Item
	$(document).on('click', '.aq-sortable-list .sortable-handle a', function() {
		var $clicked = $(this);
		
		$clicked.addClass('sortable-clicked');
		
		$clicked.parents('.aq-sortable-list').find('.sortable-body').each(function(i, el) {
			if($(el).is(':visible') && $(el).prev().find('a').hasClass('sortable-clicked') == false) {
				$(el).slideUp();
			}
		});
		$(this.parentNode.parentNode.parentNode).children('.sortable-body').slideToggle();
		
		$clicked.removeClass('sortable-clicked');
		
		return false;
	});
	
	
	// Visual Editor Block (original)
	$(document).on('sortstart', 'ul.blocks', function(event, ui) {
		if( ui != null ){
			if( ui.item.hasClass('block-aq_editor_block') ) {
				textareaID = $(ui.item).find('.wp-editor-area').attr('id');
				try { tinyMCE.execCommand('mceRemoveEditor', false, textareaID); } catch(e){
					console.log(e);
				}
			}
		}
	});

	$(document).on('sortstop', 'ul.blocks',function(event, ui) {
		if( ui != null ){
			if( ui.item.hasClass('block-aq_editor_block') ) {
				textareaID = $(ui.item).find('.wp-editor-area').attr('id');
				try { 
					tinyMCE.execCommand('mceAddEditor', false, textareaID); 
					tinyMCE.execCommand('mceAddControls', false, textareaID);
				} catch(e){
					console.log(e);
				}
			}
		}
	});

	$(document).on('resizestart', 'ul.blocks .ui-resizable', function(event, ui) {
		if( ui != null ){
			if( ui.element.hasClass('block-aq_editor_block') ) {
				textareaID = $(ui.item).find('.wp-editor-area').attr('id');
				try { tinyMCE.execCommand('mceRemoveEditor', false, textareaID); } catch(e){
					console.log(e);
				}
			}
		}
	});

	$(document).on('resizestop', 'ul.blocks .ui-resizable',function(event, ui) {
		if( ui != null ){
			if( ui.element.hasClass('block-aq_editor_block') ) {
				textareaID = $(ui.item).find('.wp-editor-area').attr('id');
				try { 
					tinyMCE.execCommand('mceAddEditor', false, textareaID); 
					tinyMCE.execCommand('mceAddControls', false, textareaID); 
				} catch(e){
					console.log(e);
				}
			}
		}
	});
	///////////////////////////////////////////////////////////////////////
	// Visual Editor Block ( AS )
	$(document).on('sortstart', 'ul.blocks', function(event, ui) {
		if( ui != null ){
			if( ui.item.hasClass('block-as_editor_block') ) {
				textareaID = $(ui.item).find('.wp-editor-area').attr('id');
				try { tinyMCE.execCommand('mceRemoveEditor', false, textareaID); } catch(e){
					console.log(e);
				}
			}
		}
	});

	$(document).on('sortstop', 'ul.blocks',function(event, ui) {
		if( ui != null ){
			if( ui.item.hasClass('block-as_editor_block') ) {
				textareaID = $(ui.item).find('.wp-editor-area').attr('id');
				try { 
					tinyMCE.execCommand('mceAddEditor', false, textareaID); 
					tinyMCE.execCommand('mceAddControls', false, textareaID);
				} catch(e){
					console.log(e);
				}
			}
		}
	});

	$(document).on('resizestart', 'ul.blocks .ui-resizable', function(event, ui) {
		if( ui != null ){
			if( ui.element.hasClass('block-as_editor_block') ) {
				textareaID = $(ui.item).find('.wp-editor-area').attr('id');
				try { tinyMCE.execCommand('mceRemoveEditor', false, textareaID); } catch(e){
					console.log(e);
				}
			}
		}
	});

	$(document).on('resizestop', 'ul.blocks .ui-resizable',function(event, ui) {
		if( ui != null ){
			if( ui.element.hasClass('block-as_editor_block') ) {
				textareaID = $(ui.item).find('.wp-editor-area').attr('id');
				try { 
					tinyMCE.execCommand('mceAddEditor', false, textareaID); 
					tinyMCE.execCommand('mceAddControls', false, textareaID); 
				} catch(e){
					console.log(e);
				}
			}
		}
	});
});
/**
 *	AS ADDITIONS
 *
 */
jQuery(document).ready(function($) {

	// Toggle Social fields in Team Member block
	$(document).on('click','.toggle-social', function(event) {
		event.preventDefault();
		$(this).parent().find('.social-fields').slideToggle(500);
	});
	
	/**
	 *	ICONS Block
	 *
	 */
	//	icons switch
	$(document).on('click','.glyphs > span', function(event) {
		
		event.preventDefault();
		/* 
		var iconCode = $(this).find('.fs1').attr('data-icon');
		var fieldDiv = $(this).parent().parent().parent().find('.icon-field');
		var previewBox = fieldDiv.find('.preview-box').find('.fs1');
		var iconInputHidden = fieldDiv.find('.icon-glyph').find('input.input-hidden');
		
		previewBox.attr( 'data-icon', iconCode );
		iconInputHidden.val( iconCode );
		 */

		var iconCode = $(this).attr('class');
		var fieldDiv = $(this).parent().parent().parent().find('.icon-field');
		var previewBox = fieldDiv.find('.preview-box').find('#preview-icon');
		var iconInputHidden = fieldDiv.find('.icon-glyph').find('input.input-hidden');
		
		previewBox.attr( 'class', iconCode );
		iconInputHidden.val( iconCode );
		
console.log(iconCode);	
	
	});	
	// icon sizes slider
	$.fn.icons_slider = function() {
	//function icons_slider() {
		$( ".slider-for-icon" ).slider({
			value: 20,
			min: 0,
			max: 100,
			slide: function( event, ui ) {
				
				var previewBox = $(this).parent().parent().find('.preview-box');
				var icon = $(this).parent().parent().find('.preview-box').find('#preview-icon');
				var inputHidden = $(this).parent().find('input.input-hidden'); // put in hidden input for save
				var displayValue = $(this).parent().find('label span'); // show value
				
				
				if( $(this).parent().hasClass('icon-size') ) {
				
					if( ui.value ) {
						icon.css('font-size', ui.value /10 + 'em');
						displayValue.text( ui.value /10 + 'em' );
						inputHidden.val( ui.value /10 );
					}
				
				}else if( $(this).parent().hasClass('icon-padding') ) {
					
					if( ui.value ) {
						icon.css('padding', Math.round( ui.value  ) + 'px');
						displayValue.text( Math.round( ui.value  ) + 'px' );
						inputHidden.val(  Math.round( ui.value ) );
					}
				
				}else if( $(this).parent().hasClass('border-width') ) {
					
					if( ui.value ) {
					icon.css('border-width', Math.round( ui.value /10 ) + 'px');
					displayValue.text( Math.round( ui.value /10 ) + 'px' );
					inputHidden.val(  Math.round( ui.value /10) );
					}
					
				}else if( $(this).parent().hasClass('border-radius') ) {
				
					if( ui.value ) {
						icon.css('border-radius', Math.round( ui.value  ) + 'px');
						displayValue.text( Math.round( ui.value  ) + 'px' );
						inputHidden.val(  Math.round( ui.value ) );
					}
					
				}else if( $(this).parent().hasClass('block-opacity') ) {
				
					if( ui.value ) {
						previewBox.css('opacity',  ui.value / 100 );
						displayValue.text( ui.value   );
						inputHidden.val(  ui.value  );
					}
					
				}else if( $(this).parent().hasClass('map-desaturation') ) {
					
					displayValue.text( Math.round( ui.value  ) + '%' );
					inputHidden.val(  Math.round( ui.value ) );
					
				}else if( $(this).parent().hasClass('zoom-level') ) {
					
					displayValue.text( Math.round( ui.value /5 ) );
					inputHidden.val(  Math.round( ui.value / 5 ) );
				}
				
			}
		});
		
	
		$( ".icon-transparent input" ).change(function() {
			var $input = $(this);
			var icon = $input.parent().parent().find('.preview-box #preview-icon');
			
			if ( $input.prop( "checked" ) ) {
				icon.css('background','transparent');
			}else
			if( !$input.prop( "checked" ) ){
				icon.css('background', icon.attr('data-backcolor') );
			}
		});
		
	};
	
	$.fn.blockControls = function() {
	
		var block_w_controls = $('#blocks-to-edit').find('li.block');
		
		block_w_controls.on( 'mouseover', function (){
			if( !$(this).hasClass('block-as_row_block') ) {
				$(this).find('.block-controls').stop().animate({'top':0 },{duration:250});
			}
		}).on('mouseout',
		function (){
			if( !$(this).hasClass('block-as_row_block') ) {
				$(this).find('.block-controls').stop().animate({'top':-20 },{duration:250});
			}
		}
		);
	}
	
	
	$(document).icons_slider();
	$(document).blockControls();

	$(document).on('sortstop','ul.blocks', function() {
		$(document).icons_slider();
		$(document).blockControls();
	});
	
	// Toggle Icon block controls
	$(document).on('click','.toggle-icon-controls', function(event) {
		event.preventDefault();
		$(this).parent().find('.slider-controls').slideToggle(500);
	});
	$(document).on('click','.toggle-icon-choice', function(event) {
		event.preventDefault();
		$(this).parent().find('.icons-controls').slideToggle(500);
	});
	$(document).on('click','.toggle-layout-text', function(event) {
		event.preventDefault();
		$(this).parent().find('.layout-text-controls').slideToggle(500);
	});
	
});