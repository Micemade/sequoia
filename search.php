<?php
/**
 * The template for displaying Search results.
 *
 * @since sequoia 1.0
 */

get_header();
//
$layout		= apply_filters( 'sequoia_options', 'layout', 'float_left' );
$enter_anim	= apply_filters( 'sequoia_options', 'post_enter_anim_archive', 'fadeIn' );
?>	
		
<header class="archive-header">

	<?php	
	$blog_title_bcktoggle = apply_filters( 'sequoia_options', 'blog_title_bcktoggle', true );
	$blog_title_backimg = apply_filters( 'sequoia_options', 'blog_title_backimg', get_template_directory_uri(). '/img/header-archive.jpg' );
	if( $blog_title_bcktoggle ) {
		
		$image =  $blog_title_backimg;
		
		echo'<div class="header-background'. ( UNDERHEAD_IMAGE ? ' under-head' : '') .'" style="background-image: url('.$image.');"></div>';
	}
	?>
	
	<div class="row">
			
		<h1 class="archive-title">
		
			<?php echo get_search_query(); ?>
			
		</h1>
				
	</div><!-- /.row -->	

</header><!-- .archive-header -->


<div class="row">

	<div class="search-results-title"><?php echo __( 'Search result:', 'sequoia' ); ?></div>

	<div id="primary" class="large-<?php echo (  $layout =='full_width' ) ? '12' : '9'; ?> <?php echo $layout ? $layout : null; ?> medium-12 small-12 column" role="main">

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 

			get_template_part( 'content', 'search' );
		
		endwhile;

			as_show_pagination() ? as_pagination( 'nav-below' ) : null;
		
		else :
		
		?>
		
		<article>
		
			<div class="search-notfound-text">
			
				<h3 class="mag_button"><?php echo __('Your search "','sequoia') . get_search_query() . __('" did not return any result.','sequoia'); ?></h3>
				
				<h5>
					<?php echo __('<ul><li>Please, try to:</li><li>click browser "Back" button,</li><li>use search to find what are you looking for,</li><li>use menu to browse our site,</li><li>or use sitemap bellow this message.</li></ul> ','sequoia') ?>
				</h5>
				
				<?php get_template_part('site','map'); ?>
			
			</div>
		
		</article><!-- #primary -->
		
		
		
		<?php endif; ?>

	</div><!-- /#primary -->

	<?php get_sidebar(); ?>
	
</div><!-- /.row -->
	
<?php
if ( $enter_anim != 'none' && !wp_style_is( 'animate', 'enqueued' )) {
				
	wp_register_style( 'animate', get_template_directory_uri() . '/css/animate.css');
	wp_enqueue_style( 'animate' );
	
}
?>
<?php if ( $enter_anim != 'none') { ?>
<script>
jQuery(document).ready( function($) {
	
	var thisBlock	= $('#primary'),
		article		= thisBlock.find('article');
	
	if ( !window.isMobile && !window.isIE9 ) {

		article.each( function() {
		
			var thisShit = $(this);
			
			thisShit.waypoint(
			
				function(direction) {
					
					if( direction === "up" ) {	
						
						thisShit.removeClass('animated <?php echo $enter_anim;?>').addClass('to-anim');
						
					}else if( direction === "down" ) {
						
						setTimeout(function(){
						   thisShit.addClass('animated <?php echo $enter_anim;?>').removeClass('to-anim');
						}, 100);
					}
				}, 
				{ offset: "98%" }	
				
			);
			
		});

	}else{

		article.each( function() {
			
			$(this).removeClass('to-anim');
		
		});
		
	}

});
</script>
<?php } // end if ?>

<?php get_footer(); ?>