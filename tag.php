<?php
/**
 *	The template for displaying Tag pages.
 *
 *	@since sequoia 1.0
 */

get_header();
// 
$layout			= apply_filters( 'sequoia_options', 'layout', 'float_left' );
?>
		
<header class="archive-header">

	<?php	
	$blog_cat_title_bcktoggle	= apply_filters( 'sequoia_options', 'blog_cat_title_bcktoggle', true );
	$blog_cat_title_backimg		= apply_filters( 'sequoia_options', 'blog_cat_title_backimg', get_template_directory_uri(). '/img/header-cats.jpg' );
	
	if( $blog_cat_title_bcktoggle ) {
		
		$image =  $blog_cat_title_backimg;
		
		echo'<div class="header-background'. ( UNDERHEAD_IMAGE ? ' under-head' : '') .'" style="background-image: url('.$image.');"></div>';
	}else{
		$image = '';
	}
	?>

	<div class="row">

		<div class="small-12">
			
		<h1 class="archive-title">
		
			<small><?php echo __('Posts tagged with:','sequoia'); ?></small>			
			
			<?php echo single_cat_title( '', false ); ?>
			
		</h1>

		<?php if ( category_description() ) : ?>
			<div class="term-description"><?php echo category_description(); ?></div>
		<?php endif; ?>
		
		</div>
		
	</div><!-- /.container -->	
		
</header><!-- .archive-header -->


<div class="row">

	<div id="primary" class="large-<?php echo ( $layout =='full_width' ) ? '12' : '9'; ?> <?php echo $layout ? $layout : null; ?> medium-12 small-12 column" role="main">

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

<?php get_footer(); ?>