<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

if( apply_filters( 'sequoia_wc_version', '3.0.0' ) ) {
	
	if ( $related_products ) : ?>

		<section class="related products">

			<h2><?php esc_html_e( 'Related products', 'sequoia' ); ?></h2>

			<?php woocommerce_product_loop_start(); ?>

				<?php foreach ( $related_products as $related_product ) : ?>

					<?php
						$post_object = get_post( $related_product->get_id() );

						setup_postdata( $GLOBALS['post'] =& $post_object );

						wc_get_template_part( 'content', 'product' ); ?>

				<?php endforeach; ?>

			<?php woocommerce_product_loop_end(); ?>

		</section>

	<?php endif;

	wp_reset_postdata();

}else{
	
	$related = $product->get_related( $posts_per_page );
	if ( sizeof( $related ) == 0 ) return;
	$args = apply_filters( 'woocommerce_related_products_args', array(
		'post_type'            => 'product',
		'ignore_sticky_posts'  => 1,
		'no_found_rows'        => 1,
		'posts_per_page'       => $posts_per_page,
		'orderby'              => $orderby,
		'post__in'             => $related,
		'post__not_in'         => array( apply_filters( 'sequoia_wc_version', '3.0.0'  ) ? $product->get_id() : $product->id )
	) );
	$products = new WP_Query( $args );
	$woocommerce_loop['columns'] = $columns;
	if ( $products->have_posts() ) : ?>
		<div class="related products">
			<h2><?php _e( 'Related Products', 'sequoia' ); ?></h2>
			<?php woocommerce_product_loop_start(); ?>
				<?php while ( $products->have_posts() ) : $products->the_post(); ?>
					<?php 
					remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_single_excerpt', 25);
					wc_get_template_part( 'content', 'product' ); ?>
				<?php endwhile; // end of the loop. ?>
			<?php woocommerce_product_loop_end(); ?>
		</div>
	<?php endif;
	wp_reset_postdata();
	
}// END 3.0.0 < Fallback conditional