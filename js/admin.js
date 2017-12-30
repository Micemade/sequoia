//var $ = jQuery.noConflict();
jQuery(document).ready( function($) {
	/**
	 *	Remove WooCommerce setting field for turn on Woo CSS:
	 *
	 */
	
	$('input#woocommerce_frontend_css').parent().parent().html('<strong>WooCommerce styles disabled by sequoia theme.</strong><p class="description">WooCommerce frontend styles are not supported by sequoia theme.</p>');
	
	
	
	/**
	 *	Custom wp_nav_menu fields control.
	 *
	 *	for mega menu control input fields.	 
	 */
	 	
	$('.custom-menu-megamenu').find('input[type=checkbox]').each( function () {
		
		var $this = $(this);
		var itemParent = $this.parent().parent().parent().parent();
		var itemTypeLabel = itemParent.find('.item-type');
		
		if( $this.prop( "checked" ) ) {
			itemTypeLabel.prepend('<span class="label-mega">Mega menu - </span>');
		}
		
		$this.on('click', function () {
			
			if ( $(this).prop( "checked" ) ) {
				itemTypeLabel.prepend('<span class="label-mega">Mega menu - </span>')
			}else{
				$(this).val('');
				itemTypeLabel.find('span.label-mega').remove();
			}	
			
		});
	
	});// end each
	 	
	$('.custom-menu-clear').find('input[type=checkbox]').each( function () {
		
		var $this = $(this);
		var itemParent = $this.parent().parent().parent().parent();
		var itemTypeLabel = itemParent.find('.item-type');
		
		if( $this.prop( "checked" ) ) {
			itemTypeLabel.prepend('<span class="label-clear">Clear (new row) - </span>');
		}
		
		$this.on('click', function () {
			
			if ( $(this).prop( "checked" ) ) {
				itemTypeLabel.prepend('<span class="label-clear">Clear (new row) - </span>')
			}else{
				$(this).val('');
				itemTypeLabel.find('span.label-clear').remove();
			}	
			
		});
	
	});// end each	
	 	
	$('.custom-menu-post_thumb').find('input[type=checkbox]').each( function () {
		
		var $this = $(this);
		var itemParent = $this.parent().parent().parent().parent();
		var itemTypeLabel = itemParent.find('.item-type');
		
		if( $this.prop( "checked" ) ) {
			itemTypeLabel.prepend('<span class="label-clear">Post thumb w. excerpt - </span>');
		}
		
		$this.on('click', function () {
			
			if ( $(this).prop( "checked" ) ) {
				itemTypeLabel.prepend('<span class="label-clear">Post thumb w. excerpt - </span>')
			}else{
				$(this).val('');
				itemTypeLabel.find('span.label-clear').remove();
			}	
			
		});
	
	});// end each	
	
	$('.custom-menu-image').find('input.input-upload').each( function () {
		
		var $this = $(this);
		var itemParent = $this.parent().parent().parent().parent().parent();
		var itemTypeLabel = itemParent.find('.item-type');
		
		if( $.trim(this.value).length ) {
			itemTypeLabel.prepend('<span class="label-image">Custom image - </span>');
		}
		
		$this.parent().find('.remove-media').on('click', function () {

			$(this).val('');
			itemTypeLabel.find('span.label-image').remove();	
		
		});
	
	});// end each
	
	/** 
	 *	Media Uploader
	 *
	 */
	$(document).on('click', '.as_upload_button', function(event) {
		var $clicked = $(this), frame,
			input_id = $clicked.prev().attr('id'),
			img_size = $clicked.prev().attr("data-size"),
			media_type = $clicked.attr('rel');
			itemParent = $clicked.parent().parent().parent().parent().parent(); // main menu holder (li)
			itemTypeLabel = itemParent.find('.item-type'); // menu item label
			
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
			
			if(media_type == 'image') $('#' + input_id).parent().parent().parent().find('.image-holder img.att-image').attr('src', attachment.attributes.sizes[img_size].url);
			
			itemTypeLabel.prepend('<span class="label-image">Image - </span>');
			
		});

		frame.open();
	
	});
	$(document).on('click', 'a.remove-media', function(event) {
		
		event.preventDefault();
		
		var imgDiv = $(this).parent().parent().find('.image-holder');
		var placeHolderImg = imgDiv.find('input.placeholder').val();
		
		imgDiv.find('img.att-image').attr('src', placeHolderImg );
		
		$(this).parent().parent().find('input.input-upload').val('');
		
	});
	
	function stickyMenu( menu_to_stick, trigger ) {
		
		if( menu_to_stick.length && trigger.length ) {
		
			var trig_position = trigger.offset().top - $(window).scrollTop();
			
			if( trig_position  <= 0 ) {
				
				if( menu_to_stick.hasClass('stick-it') ) // if already exists stop
					return;
				
				menu_to_stick.addClass('stick-it');
				
			
			}else{
				
				menu_to_stick.removeClass('stick-it');
			}
		
		}
	}
		
	$(window).scroll(function() {
		stickyMenu( $('#of_container #of-nav' ), $('#of_container #content') );
		
	});
	$(window).load(function() {
		stickyMenu( $('#of_container #of-nav' ), $('#of_container #content') );
	});

	/*
	 * SEQUOIA 1.3.1 UPDATE NOTICE
	 */

	$(document).on( 'click', '.sequoia-131-notice .notice-dismiss', function() {

		$.ajax({
			url: ajaxurl,
			data: {
				action: 'sequoia-update-option'
			}
		})
	})
		
	
});
