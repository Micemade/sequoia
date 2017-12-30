<?php
/**
 *	The Blank footer template.
 *
 *	@since sequoia 1.0
 */
?>

</div><!-- end #page -->
	
<footer id="footer" style="margin: 0; padding: 0; border: none;"></footer>

<?php 
// SOME WOOCOMMERCE STUFF:
global $woocommerce, $sequoia_woo_is_active;

if( $sequoia_woo_is_active ) {

	if( function_exists( 'wc_notice_count' ) ) {
		
		if( wc_notice_count() ) {
			echo '<div class="theme-shop-message">';
			do_action( 'woocommerce_before_single_product' );
			echo '</div>';
		}
		
	}else{
		// backward  < 2.1 compatibility:
		if( $woocommerce->error_count() > 0 || $woocommerce->message_count() > 0 ) {
			echo '<div class="theme-shop-message">';
			do_action( 'woocommerce_before_single_product' );
			echo '</div>';
		}
	}
	
}
?>

<?php wp_footer(); ?>	


</body>

</html>