<?php
/**
 * Template Name: Page builder template
 *
 * Page for template builder - bulit for using row blocks
 *
 * @since sequoia1.0
 **/
get_header();
// 
global $post;

$page_under_head		=  get_post_meta( get_the_ID() ,'as_page_under_head', true);
$under_head = ( $page_under_head == false && UNDERHEAD_IMAGE ) ? ' under-head' : '';
//
//
$hide_title = get_post_meta( get_the_ID() ,'as_hide_title');
//
// VARS IF IT'S  SHOP:
if( $sequoia_woo_is_active ) {
	$is_shop = ( is_shop() || is_woocommerce() || is_cart() || is_checkout() || is_account_page()) ? true : false ;
}else{
	$is_shop = false;
}
//
if( !$hide_title  ) {
?>
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
}; // end if !$hide_title ?>	


<section id="post-<?php the_ID(); ?>" <?php post_class(); ?> >	
		
	<?php
	
	if( have_posts() ) : while ( have_posts() ) : the_post(); 
	
		the_content();
			
	endwhile;
	
	endif;
	
	?>

	<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'sequoia' ), 'after' => '</div>' ) ); ?>
				
	<div class="clearfix"></div>

</section>
	
<?php get_footer(); ?>