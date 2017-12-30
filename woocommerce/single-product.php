<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header('shop'); 
global $under_header_class;
//
$layout						= apply_filters( 'sequoia_options', 'layout', 'float_left' );
$single_full_width			= apply_filters( 'sequoia_options', 'single_full_width', 'horizontal' );
/**
 * DISCARDED:
 * woocommerce_before_main_content hook
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
//do_action('woocommerce_before_main_content'); // COPIED TO "breadcrumb-slogans.php" file
?>
<header class="page-header">

	<?php	
	$shop_title_bcktoggle	= apply_filters( 'sequoia_options', 'shop_title_bcktoggle', true ); // if bckimg is set to true
	$shop_title_backimg		= apply_filters( 'sequoia_options', 'shop_title_backimg', get_template_directory_uri(). '/img/header-shop.jpg' ); // general blog bckimg
	
	if( $shop_title_bcktoggle ) {

		$attach_ID			= get_post_thumbnail_id(); // featured image
		$custom_head_image	= get_post_meta( get_the_ID(), 'as_custom_head_image', true); // custom head image
		$custom_head_format	= get_post_meta( get_the_ID(), 'as_custom_head_image_format', true);
		$custom_head_repeat	= get_post_meta( get_the_ID(), 'as_custom_head_image_repeat', true);
		$custom_head_size	= get_post_meta( get_the_ID(), 'as_custom_head_image_size', true);
		
		$img_format = $custom_head_format ? $custom_head_format : 'as-landscape';
		
		
		if( $custom_head_image ) { // if custom head image
		
			if( $custom_head_repeat || $custom_head_size ) {
				echo '<style>.header-background {';
				echo $custom_head_repeat	? 'background-repeat:'.$custom_head_repeat.';' : '';
				echo $custom_head_size		? 'background-size:'.$custom_head_size.' !important;' : '';
				echo '}</style>';
			}
			
			$image = wp_get_attachment_image_src( $custom_head_image , $img_format );
			$image = $image[0];
			
		}elseif( $attach_ID ){ // else if featured product (post thumbnail)
			
			$image = wp_get_attachment_image_src( $attach_ID, 'as-landscape' );
			$image = $image[0];
		
		}else{ // else do the theme options image
		
			$image =  $shop_title_backimg;
			
		}// or, no image
	
		echo'<div class="header-background'.( UNDERHEAD_IMAGE ? ' under-head' : '').'" style="background-image: url('.$image.');"></div>';
	}
	?>

	<div class="row">
	
		<div class="large-12">
		
		<?php do_action( 'as_single_product_summary' ); ?>
		
		</div>
	
	</div>
	
</header>


<div class="row">

	<div id="primary" class="large-<?php echo ( $layout =='full_width' || $single_full_width ) ? '12' : '9'; ?> <?php echo $layout ? $layout : null; ?> medium-12 small-12 column" role="main">
	
	<?php while ( have_posts() ) : the_post(); ?>

		<?php wc_get_template_part( 'content', 'single-product' ); ?>

	<?php endwhile; // end of the loop. ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');
	?>

	</div>
	
	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		if( !$single_full_width && $layout !='full_width' ) {
			do_action('woocommerce_sidebar');
		}
		?>

</div><!-- /.container -->

<?php get_footer('shop'); ?>