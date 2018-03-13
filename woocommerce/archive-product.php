<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
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
 * @version     3.3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header( 'shop' );

global $under_header_class;
// WC 3.3.0 check
$wc_330 = apply_filters( 'sequoia_wc_version', '3.3.0' );

$layout              = apply_filters( 'sequoia_options', 'layout', 'float_left' );
$products_full_width = apply_filters( 'sequoia_options', 'products_full_width', true );
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

<header class="page-header<?php echo $under_header_class; ?>">

	<?php
	$shop_title_bcktoggle = apply_filters( 'sequoia_options', 'shop_title_bcktoggle', true );
	$shop_title_backimg = apply_filters( 'sequoia_options', 'shop_title_backimg', get_template_directory_uri() . '/img/header-shop.jpg' );
	if ( $shop_title_bcktoggle ) {

		if ( is_tax( 'product_cat' ) ){
			// $term - current taxonomy term id
			$term = get_term_by('slug', esc_attr( get_query_var('product_cat') ), 'product_cat');
			// WooCommerce meta - thumbnail_id:
			$thumbnail_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id' );
		} else {
			$thumbnail_id = '';
		}

		if( $thumbnail_id ) {
			// get image by attachment id:
			$image = wp_get_attachment_image_src( $thumbnail_id, 'as-landscape' );
			$image = $image[0];
		} else {
			$image =  $shop_title_backimg;
		}

		echo'<div class="header-background' . ( UNDERHEAD_IMAGE ? ' under-head' : '' ) . '" style="background-image: url(' . esc_url( $image ) . ');"></div>';
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

	<div id="primary" class="large-<?php echo ( 'full_width' === $layout || $products_full_width ) ? '12' : '9'; ?> <?php echo esc_attr( $layout ? $layout : null ); ?> medium-12 small-12 column" role="main">

		<?php
		//META BOX FROM SHOP PAGE - insert meta content ( wyswyg ) before and/or after products.
		$before_catalog = get_post_meta( wc_get_page_id( 'shop' ), 'as_before_catalog', true );
		$after_catalog  = get_post_meta( wc_get_page_id( 'shop' ), 'as_after_catalog', true );

		echo '<div class="before-catalog">' . do_shortcode( $before_catalog ) . '</div>';
		?>

		<div class="clearfix"></div>

		<?php if ( have_posts() ) { ?>

			<?php do_action( 'sequoia_top_catalog_widgets' ); ?>

			<?php
			/**
			 * WooCommerce_before_shop_loop hook
			 *
			 * @hooked woocommerce_result_count - 20
			 * @hooked woocommerce_catalog_ordering - 30
			 */
			do_action( 'woocommerce_before_shop_loop' );
			?>

			<div class="clearfix"></div>

			<?php
			woocommerce_product_loop_start();

			// WC 3.3.0 check
			if ( $wc_330 ) {
				if ( wc_get_loop_prop( 'total' ) ) {
					while ( have_posts() ) {
						the_post();
						/**
						 * Hook: woocommerce_shop_loop.
						 *
						 * @hooked WC_Structured_Data::generate_product_data() - 10
						 */
						do_action( 'woocommerce_shop_loop' );
						wc_get_template_part( 'content', 'product' );
					}
				}
				// fallback < WC 3.3.0.
			} else {
				while ( have_posts() ) { 
					the_post();
					wc_get_template_part( 'content', 'product' );
				};
			}
			// endif.

				woocommerce_product_loop_end();

				/**
				 * WooCommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );

			} else {

				do_action( 'woocommerce_no_products_found' );

			}

			?>

		<?php
			/**
			 * WooCommerce_after_main_content hook
			 *
			 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action( 'woocommerce_after_main_content' );

			echo '<div class="after-catalog">' . do_shortcode( $after_catalog ) . '</div>';
		?>

	</div><!-- #primary -->

	<?php
	/**
	 * WooCommerce_sidebar hook
	 *
	 * @hooked woocommerce_get_sidebar - 10
	 */

	if ( ! $products_full_width && 'full_width' !== $layout ) {
		do_action( 'woocommerce_sidebar' );
	}
	?>

</div><!-- .row -->

<?php get_footer( 'shop' );
