<?php
/**
 * The 404 page - 
 *
 *
 * @since sequoia1.0
 **/
get_header(); 
?>	

					
<header class="page-header">
	
	<?php	
	$blog_title_bcktoggle	= apply_filters( 'sequoia_options', 'blog_title_bcktoggle', true );
	$blog_title_backimg		= apply_filters( 'sequoia_options', 'blog_title_backimg', true );
	if( $blog_title_bcktoggle ) {
		
		$image =  $blog_title_backimg;
		
		echo'<div class="header-background'. ( UNDERHEAD_IMAGE ? ' under-head' : '') .'" style="background-image: url('.$image.');"></div>';
	}
	?>
	
	<div class="row">
	
		<div class="large-12">
		
			<h1 class="page-title"><?php _e( 'Page missing - 404', 'sequoia' ); ?></h1>
	
		</div>
		
	</div><!-- /.row -->

</header>


<div class="row">

	<div id="primary" role="main" class="large-12 column">
		
		<article id="post-0" class="post error404 not-found" style="margin-top: 3rem">

		<div aria-hidden="true" data-icon="&#xe08c;">
			<h3><?php echo __('Something went wrong','sequoia')?></h3>
			<h5><?php echo __("This search can help you find what you need",'sequoia')?></h5>
			<?php get_template_part('searchform','nav'); ?>
		</div>

		<hr>		
		
		<h4><?php echo __("Reason why you're seeing this page could be one of the following:",'sequoia')?></h4>
			
			
		
			<?php echo __('<ul><li>You clicked the broken link</li><li>You typed incorrect link directly in the address bar</li><li>There is a glitch in the server, database or system, or</li><li>Maybe, but just maybe, it might be our mistake</li></ul> ','sequoia') ?>
			
		
		
				
		</article>
		
	</div><!-- #primary -->		

</div><!-- /.row -->
	
<?php get_footer(); ?>