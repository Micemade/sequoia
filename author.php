<?php
/**
 *	The template for displaying Author Archive pages.
 *
 *	@since sequoia 1.0
 */

get_header();
// 
$layout		= apply_filters( 'sequoia_options', 'layout', 'float_left' );
$enter_anim = apply_filters( 'sequoia_options', 'post_enter_anim_tax', 'fadeIn' );
?>

<header class="archive-header">
	
	<?php	
	$blog_author_title_bcktoggle = apply_filters( 'sequoia_options', 'blog_author_title_bcktoggle', true );
	$blog_author_title_backimg = apply_filters( 'sequoia_options', 'blog_author_title_backimg', get_template_directory_uri(). '/img/header-author.jpg' );
	if( $blog_author_title_bcktoggle ) {
		
		$image =  $blog_author_title_backimg;
		
		echo'<div class="header-background'. ( UNDERHEAD_IMAGE ? ' under-head' : '') .'" style="background-image: url('.$image.');"></div>';
	}else{
		$image = '';
	}
	?>
	
	<div class="row">
	
		<div class="small-12">
		
		<h1 class="archive-title">
		<?php 
		if ( have_posts() ): the_post();
		printf( __( '<small>Author Archives:</small> %s', 'sequoia' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
		endif;
		?>
		</h1>
		
		</div>
		
	</div><!-- /.row -->		
		
</header><!-- .archive-header -->


<div class="row">

	<div id="primary" class="large-<?php echo ( $layout =='full_width' ) ? '12' : '9'; ?> <?php echo $layout ? $layout : null; ?> medium-12 small-12 column" role="main">

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post();

			get_template_part( 'content', get_post_format() );

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