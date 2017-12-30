<?php
/**
 * Product Loop End
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
$enter_anim		= apply_filters( 'sequoia_options', 'prod_enter_anim', 'fadeIn' );
?>
</ul>
<?php
if ( $enter_anim != 'none' && !wp_style_is( 'animate', 'enqueued' )) {
		
	wp_register_style( 'animate', get_template_directory_uri() . '/css/animate.css');
	wp_enqueue_style( 'animate' );
	
}
?>
<?php if ( $enter_anim != 'none') { ?>
<script>
(function( $ ){
	$.fn.anim_waypoints_catalog = function( enter_anim ) {
		
		var thisBlock = $('.products' ),
			item = thisBlock.find('.item');
			
		if ( !window.isMobile && !window.isIE9 ) {
			
			item.each( function() {
			
				var eachItem = $(this);
				
				eachItem.waypoint(
				
					function(direction) {
						
						if( direction === "up" ) {	
						
							eachItem.removeClass('animated '+ enter_anim).addClass('to-anim');
							
						}else if( direction === "down" ) {
						
							var i =  $(this).attr('data-i');
							setTimeout(function(){
							   eachItem.addClass('animated '+ enter_anim).removeClass('to-anim');
							}, 50 * i);
						}
					}
					
					,{ offset: "98%" }
				);
			
			});
			
		}else{
		
			item.each( function() {
				$(this).removeClass('to-anim');
			});
			
		}
		
	}
})( jQuery );

jQuery(document).ready( function($) {
	
	
	$(document).anim_waypoints_catalog(" <?php echo $enter_anim;?>");

});
</script>
<?php } // end if ?>