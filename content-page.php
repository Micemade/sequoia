<?php
/**
 *	The template part used for displaying page content in page.php.
 *
 *	@since sequoia 1.0
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//
//
$has_content = get_the_content();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php echo ( !$has_content ? 'style="margin-bottom:0;"' : '' ); ?>>

	<div class="post-content">	
	
		<?php the_content(); ?>
			
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'sequoia' ), 'after' => '</div>' ) ); ?>
						
	</div>
	
	<div class="clearfix"></div>
	

</article><!-- #post-<?php the_ID(); ?> -->
