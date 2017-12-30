<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header('shop'); 
global $under_header_class;
//
$layout						= apply_filters( 'sequoia_options', 'layout', 'float_left' );
$products_full_width		= apply_filters( 'sequoia_options', 'products_full_width', true );
//
?>

<?php
	/**
	 * woocommerce_before_main_content hook
	 *
	 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
	 * @hooked woocommerce_breadcrumb - 20
	
	do_action('woocommerce_before_main_content'); // COPIED TO "breadcrumbs.php" file in theme root
	*/ 
?>

<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

<header class="page-header<?php echo $under_header_class;?>">
	
	<?php	
	$shop_title_bcktoggle = apply_filters( 'sequoia_options', 'shop_title_bcktoggle', true );
	$shop_title_backimg = apply_filters( 'sequoia_options', 'shop_title_backimg', get_template_directory_uri(). '/img/header-shop.jpg' );
	if( $shop_title_bcktoggle ) {
		
		if(  is_tax( 'product_cat' ) ){
			// $term - current taxonomy term id
			$term = get_term_by('slug', esc_attr( get_query_var('product_cat') ), 'product_cat');
			// woocommerce meta - thumbnail_id:
			$thumbnail_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id' );
		}else{
			$thumbnail_id = '';
		}
		
		if( $thumbnail_id ) {
			// get image by attachment id:
			$image = wp_get_attachment_image_src( $thumbnail_id, 'as-landscape' );
			$image = $image[0];
		}else{
			$image =  $shop_title_backimg;
		}
		
		echo'<div class="header-background'.( UNDERHEAD_IMAGE ? ' under-head' : '').'" style="background-image: url('.$image.');"></div>';
	}
	?>
	
	<div class="row">
	
		<div class="small-12">
		
			<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
						
		</div><!-- .small-12 -->
		
	</div><!-- .row -->


</header>

<?php endif; ?>

<div class="row">

	<?php do_action( 'woocommerce_archive_description' ); ?>	
	
	<div id="primary" class="large-<?php echo ( $layout =='full_width' || $products_full_width ) ? '12' : '9'; ?> <?php echo $layout ? $layout : null; ?> medium-12 small-12 column" role="main">
	
		<?php		
		
		if ( is_active_sidebar( 'product-filters-widgets' ) ) {
			
			echo '<div class="row"><div class="product-filters-wrap">';

			echo '<div class="product-filters">';
			
			echo '<span class="icon icon-close"></span>';	
			
				dynamic_sidebar( 'product-filters-widgets' ); 
				
				dynamic_sidebar( 'layered-nav-filter-widgets' ); 
				
			
			echo '<div class="clearfix"></div></div>';
			
			echo '<h4 class="product-filters-title">'. __('Product filters','sequoia') .'</h4>';
						
			echo '</div></div>'; // product-filters-wrap 
			
			echo '<div class="product-filters-clearer"></div>';
		
		}
		
		//META BOX FROM SHOP PAGE - insert meta content ( wyswyg ) before and/or after products:
		
		$before_catalog		= get_post_meta( wc_get_page_id('shop'), 'as_before_catalog', true);
		$after_catalog		= get_post_meta( wc_get_page_id('shop'), 'as_after_catalog', true);
		
		echo '<div class="before-catalog">'. do_shortcode($before_catalog) .'</div>';
		
		?>
		<div class="clearfix"></div>
		
		<?php if ( have_posts() ) : ?>

			<?php
			
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );

			?>
			
			<div class="clearfix"></div>
			
			<?php woocommerce_product_subcategories(array('before'=>'<div class="categories-title-holder"><h2 class="categories-title block-title ">'.__('Product categories','sequoia').'</h2></div><div class="product-categories">', 'after'=>'<div class="clearfix"></div></div>', 'force_display'=> true)); ?>
				
			<?php woocommerce_product_loop_start(); ?>
				
			
			<?php while ( have_posts() ) : the_post(); ?>

				<?php wc_get_template_part( 'content', 'product' ); ?>
					
			<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

		<?php
			/**
			 * woocommerce_after_main_content hook
			 *
			 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action('woocommerce_after_main_content');
			
			echo '<div class="after-catalog">'. do_shortcode($after_catalog) .'</div>';
			
		?>

	</div><!-- #primary -->
	
	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		
		if ( !$products_full_width && $layout !='full_width' ) { 
			do_action('woocommerce_sidebar');
		}
	?>

</div><!-- .row -->

<?php get_footer('shop'); ?>