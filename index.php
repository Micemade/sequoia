<?php
/**
 *	The main template file for blog posts.
 *
 *	@since sequoia 1.0 
 */

get_header();
//
$layout			= apply_filters( 'sequoia_options', 'layout', 'float_left' );
$index_title	= apply_filters( 'sequoia_options', 'index_title', true );
$enter_anim		= apply_filters( 'sequoia_options', 'post_enter_anim_archive', 'fadeIn' );
?>

<header class="archive-header">

	<?php	
	$index_title_bcktoggle = apply_filters( 'sequoia_options', 'index_title_bcktoggle', true );
	$index_title_backimg = apply_filters( 'sequoia_options', 'index_title_backimg', get_template_directory_uri(). '/img/header-portfolio.jpg' );
	if( $index_title_bcktoggle ) {
		
		$image =  $index_title_backimg;
		
		echo'<div class="header-background'. ( UNDERHEAD_IMAGE ? ' under-head' : '') .'" style="background-image: url('.$image.');"></div>';
	}
	?>
	<div class="row">
		
		<?php if( $index_title ) { ?>
		<div class="small-12">
		
			<h1 class="archive-title"><?php bloginfo( 'name' );?></h1>

		</div>
		<?php }else{ ?>
			
			<div style="height:80px; display: block; background: none;"></div>
		
		<?php } ?>
		
	</div><!-- /.row -->	
	

</header><!-- .archive-header -->


<div class="row">
	
	<div class="site-desc-index"><?php bloginfo( 'description' );?></div>
	
	<div id="primary" class="large-<?php echo ( $layout =='full_width' ) ? '12' : '8'; ?> <?php echo $layout ? $layout : null; ?> medium-12 small-12 column" role="main">

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>
			
				<?php get_template_part( 'content', get_post_format() ); ?>
				
			<?php endwhile; 
			
				as_show_pagination() ? as_pagination( 'nav-below' ) : null;
			
			else : 
			?>

			<article id="post-0" class="post no-results not-found">

			<?php if ( current_user_can( 'edit_posts' ) ) :
				// Show a different message to a logged-in user who can add posts.
			?>
					
				<h2 class="post-title"><?php _e( 'No posts to display', 'sequoia' ); ?></h2>

				<div class="post-content">
				
					<p><?php printf( __( 'Ready to publish your first post? <a href="%s">Get started here</a>.', 'sequoia' ), admin_url( 'post-new.php' ) ); ?></p>
					
				</div><!-- .entry-content -->

			<?php else :
				// Show the default message to everyone else.
			?>
				
				<h2 class="post-title"><?php _e( 'No posts found on this site', 'sequoia' ); ?></h2>
				

				<div class="post-content">
				
					<p><?php _e( 'We are sorry, but no results were found. Try search ti find a related post.', 'sequoia' ); ?></p>
					
					<?php get_search_form(); ?>
				
				</div><!-- .entry-content -->
				
			<?php endif; // end current_user_can() ?>

			</article><!-- #post-0 -->

		<?php endif; // end have_posts() ?>

	</div><!-- #primary -->

	<?php get_sidebar(); ?>
	
</div><!-- .row -->

<?php if ( $enter_anim != 'none') { ?>
<script>
jQuery(document).ready( function($) {
	
	var thisBlock	= $('#primary'),
		article		= thisBlock.find('article');
	
	if ( !window.isMobile && !window.isIE9 ) {

		article.each( function() {
		
			var $_this = $(this);
			
			$_this.waypoint(
			
				function(direction) {
					
					if( direction === "up" ) {	
						
						$_this.removeClass('animated <?php echo $enter_anim;?>').addClass('to-anim');
						
					}else if( direction === "down" ) {
						
						setTimeout(function(){
						   $_this.addClass('animated <?php echo $enter_anim;?>').removeClass('to-anim');
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