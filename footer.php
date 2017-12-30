<?php
/**
 *	The Footer template.
 *
 *	@since sequoia 1.0
 */
?>

</div><!-- end #page -->

<?php
/**
*	HEADER AND MENU ORIENTATION:
*/
$orientation		= apply_filters( 'sequoia_options', 'orientation', 'horizontal' );
$predefined_headers = apply_filters( 'sequoia_options', 'predefined_headers', '01' );
if( $orientation == 'vertical' ) {
	$page_layout = ' vertical';
}else{
	$page_layout = ' horizontal';
}
?>
	
	<footer id="footer" class="<?php echo $page_layout; ?>">
	
		<?php // FOOTER WIDGETS //////////////////////////////// ?>
		
		<?php if ( is_active_sidebar( 'footer-widgets-1' ) || is_active_sidebar( 'footer-widgets-2' ) || is_active_sidebar( 'footer-widgets-3' ) ) : ?>

			<div id="footerwidgets">
				
				<div class="row">		
										
					<?php 
					if ( is_active_sidebar( 'footer-widgets-1' ) ) {
						echo '<div>';
						dynamic_sidebar( 'footer-widgets-1' ); 
						echo '</div>';
					}
					if ( is_active_sidebar( 'footer-widgets-2' ) ){
						echo '<div>';
						dynamic_sidebar( 'footer-widgets-2' ); 
						echo '</div>';
					}		
					if ( is_active_sidebar( 'footer-widgets-3' ) ) {
						echo '<div>';
						dynamic_sidebar( 'footer-widgets-3' ); 
						echo '</div>';
					}
					if ( is_active_sidebar( 'footer-widgets-4' ) ) {
						echo '<div>';
						dynamic_sidebar( 'footer-widgets-4' ); 
						echo '</div>';
					}	
					?>
							
				</div><!-- / .row -->
			
			</div>

		
		<?php endif; ?>
		
		<div class="credits">
		
			<div class="row">
				
				<?php 
				$footer_text = apply_filters( 'sequoia_options', 'footer_text', '' );
				if ( $footer_text ) {
					
					echo wp_kses_post( $footer_text );

				}else{?>
				
					<p>&copy; <?php bloginfo('blog_url'); ?> <?php echo get_bloginfo('description') ? ' | '. get_bloginfo('description') : ''; ?></p>
				
				<?php }; // endif ?>
			
			</div> <!-- /row -->
		</div>
		
	</footer>

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

<a href="#0" class="to-top button icon-arrow-up-6" title="<?php echo esc_attr(__('Top','sequoia')); ?>"></a>


<?php 
##  IF SIMPLE HORIZONTAL HEADER IS SELECTED
if( $orientation == 'horizontal' && $predefined_headers == 'simple' ) {?>
</div><!-- .st-content -->

</div><!-- .st-pusher -->

</div><!-- #st-container -->
<?php } ?>

<?php wp_footer(); ?>

<div class="clearfix"></div>

</div><!-- end .bodywrap -->

</body>

</html>