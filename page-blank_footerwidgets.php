<?php
/**
 * Template Name: Blank with footer widgets
 *
 * Template best suitable for landing pages
 * - uses custom header ( no menus and title )
 *
 * @since sequoia1.0
 **/

// Custom header for blank template
get_header('blank'); 
?>

<section id="post-<?php the_ID(); ?>" <?php post_class(); ?> >	
		
	<?php
	
	if( have_posts() ) : while ( have_posts() ) : the_post(); 
	
		the_content();
	
	endwhile;
	
	endif;
	
	wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'sequoia' ), 'after' => '</div>' ) ); ?>
	
	<div class="clearfix"></div>

</section>
	
<?php get_footer('blank_widgets'); ?>