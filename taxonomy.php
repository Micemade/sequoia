<?php
/**
 * The template for displaying Taxonomy pages.
 *
 * @since sequoia 1.0
 */

get_header();
// 
$layout		= apply_filters( 'sequoia_options', 'layout', 'float_left' );
$enter_anim = apply_filters( 'sequoia_options', 'post_enter_anim_tax', 'fadeIn' );
?>

<header class="archive-header">

	<?php	
	$portfolio_title_bcktoggle	= apply_filters( 'sequoia_options', 'portfolio_title_bcktoggle', true );
	$portfolio_title_backimg	= apply_filters( 'sequoia_options', 'portfolio_title_backimg', get_template_directory_uri(). '/img/header-portfolio.jpg' );
	
	if( $portfolio_title_bcktoggle ) {
		
		$image =  $portfolio_title_backimg;
		
		echo'<div class="header-background'. ( UNDERHEAD_IMAGE ? ' under-head' : '') .'" style="background-image: url('.$image.');"></div>';
	}else{
		$image = '';
	}
	?>
	
	<div class="row">		

		<div class="small-12">
		
		<h1 class="archive-title">
		
			<small>
				<?php 
				$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				echo ucfirst( str_replace('_',' ',$term->taxonomy) );
				?> :
			</small>

			<?php echo $term->name; ?>
		
		</h1>

		
		</div>
		
	</div><!-- /.container -->	
	
</header><!-- .archive-header -->
	
	
<div class="row">

	<?php if ( category_description() ) : ?>
		<div class="term-description"><?php echo category_description(); ?></div>
	<?php endif; ?>

	
	<div id="primary" class="large-<?php echo (  $layout =='full_width' ) ? '12' : '9'; ?> <?php echo $layout ? $layout : null; ?> medium-12 small-12 column" role="main">

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<?php get_template_part( 'content', get_post_format() );

			endwhile;

			as_show_pagination() ? as_pagination( 'nav-below' ) : null;
			
		else :
		
			get_template_part( 'content', 'empty' );
		
		endif; ?>

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