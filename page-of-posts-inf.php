<?php
/**
 * Template Name: Page of posts - INFINITE load
 *
 * Page to display posts archive with ajax infinte post load
 *
 * @since sequoia1.0
 **/
get_header(); 
// 
global $sequoia_woo_is_active;
//
$layout 			= apply_filters( 'sequoia_options', 'layout', 'float_left' );
$enter_anim 		= apply_filters( 'sequoia_options', 'post_enter_anim_archive', 'fadeIn' );
$page_under_head	=  get_post_meta( get_the_ID() ,'as_page_under_head', true);
$under_head = ( $page_under_head == false && UNDERHEAD_IMAGE ) ? ' under-head' : '';
// CUSTOM META:
$hide_title =  get_post_meta( get_the_ID() ,'as_hide_title', true);
// VARS FOR FULL WIDTH AND/OR SHOP:
if( $sequoia_woo_is_active ) {
	$is_shop = ( is_shop() || is_woocommerce() || is_cart() || is_checkout() || is_account_page()) ? true : false ;
}else{
	$is_shop = false;
}
?>

<?php if( !$hide_title  ) { ?>				
<header class="page-header">

	<?php	
	$shop_title_bcktoggle = apply_filters( 'sequoia_options', 'shop_title_bcktoggle', true );
	$shop_title_backimg = apply_filters( 'sequoia_options', 'shop_title_backimg', get_template_directory_uri(). '/img/header-shop.jpg' );
	
	if( $shop_title_bcktoggle && $shop_title_backimg && $is_shop ) {
		
		$image =  $shop_title_backimg;
		
		echo'<div class="header-background'. $under_head .'" style="background-image: url('.$image.');"></div>';
		
	}elseif( has_post_thumbnail() ) {
		// get image by attachment id:
		$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'as-landscape' );
		$image = $image[0];
		
		echo'<div class="header-background'. $under_head .'" style="background-image: url('.$image.');"></div>';
	}else{
		$image = '';
	}
	?>
	
	<div class="row">
	
		<div class="small-12">
		
			<h1 class="page-title"><?php the_title(); ?></h1>
		
		</div>
		
	</div><!-- /.row -->

</header>

<?php  
} // end if !hide_title
?>

<div class="row">
	
	<div id="primary" class="large-<?php echo ( $layout =='full_width' ) ? '12' : '9'; ?> <?php echo $layout ? $layout : null; ?> medium-12 small-12 column" role="main">

		
		<?php while ( have_posts() && get_the_content() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page' ); ?>

		<?php endwhile;  ?>

		<?php
		/**
		 *	LOAD POSTS
		 */

		$args = array(
				'orderby'	=> 'date',
				'order'		=> 'DESC',
				'status'	=> 'publish',
				'ignore_sticky_posts' => 1
				);
		query_posts( $args );
		?>
		
		
		<?php if ( have_posts() ) : ?>

			<section class="infinite-posts">
			
				<?php while ( have_posts() ) : the_post(); ?>
								
				<article class="infinite-post">
					
					<h2 class="post-title"><a href="<?php esc_attr(the_permalink()); ?>" title="<?php the_title_attribute(); ?>"><?php the_title();?></a></h2>
					
					<div class="simple-meta"><?php as_entry_author(); as_entry_date();?></div>
					
					<div class="post-content"><?php the_excerpt();?></div>					
				
				</article>
				
				<?php endwhile; ?>
			
			</section>
			
			<script type="text/javascript">
			post_offset = increment = <?php echo get_option( 'posts_per_page' );?> ;
			</script>

		<?php endif; // end have_posts() ?>

	</div><!-- #primary -->

	<?php
	if( $layout != 'full_width' ) {
		
		$is_shop ? do_action('woocommerce_sidebar') : get_sidebar();
	}
	?>
	
</div><!-- .row -->

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