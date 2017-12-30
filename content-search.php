<?php
/**
 *	The template part for search results.
 *
 *	@since sequoia 1.0 
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//
$archive_enteranim	= apply_filters( 'sequoia_options', 'post_enter_anim_archive', 'fadeIn' );
$tax_enteranim		= apply_filters( 'sequoia_options', 'post_enter_anim_tax', 'fadeIn' );
$enter_anim			= $archive_enteranim ? $archive_enteranim : $tax_enteranim;
//
//
$classes = array();
$classes[] = ($enter_anim != 'none') ? ' to-anim' : '';
?>


<article id="post-<?php the_ID(); ?>" <?php post_class($classes); ?>>

	<a href="<?php the_permalink(); ?>" class="post-link" title="<?php echo get_the_title() ?>" >
		
		<div class="search-text">
		
			<h2 class="post-title"><?php the_title(); ?></h2>	
			
		</div>
	</a>
	
	<div class="post-content">
	
		<?php the_excerpt(); ?>
		
	</div>
	
	<div class="clearfix"></div>
	
	
</article><!-- #post-<?php the_ID(); ?> -->

<div class="clearfix"></div>