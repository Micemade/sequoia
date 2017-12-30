<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop, $wp_query, $sequoia_wishlist_is_active;


// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) 
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', loop_columns() );

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

 
// SEQUOIA THEME EDITS:
$classes	= array();
$enter_anim	= apply_filters( 'sequoia_options', 'prod_enter_anim', 'fadeIn' );
// for responsive grid:
if( $woocommerce_loop['columns'] % 2 == 0 ){ // more then 1 item and even
	$oe = '6';
}else{		// more then 1 item and odd
	$oe = '4';
};
$classes[] = 'large-' . floor( 12 / $woocommerce_loop['columns'] ) ;
$classes[] = 'item medium-'.$oe. ' small-12 column item';
$classes[] = ($enter_anim != 'none') ? ' to-anim' : '';
//
// Remove opening and closing link tags
remove_action( 'woocommerce_before_shop_loop_item','woocommerce_template_loop_product_link_open' );  // WC 2.5.0 >
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);  // WC 2.5.0 >
// end SEQUOIA THEME EDITS:
?>
<li <?php post_class( $classes ); ?> data-i="<?php echo $woocommerce_loop['loop']; ?>">
	
	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
		
		<?php
		$products_settings = apply_filters( 'sequoia_options', 'products_settings', array( 'disable_zoom_button' => false, 'disable_link_button' => false ) ); 
		$zoom_button = !isset($products_settings['disable_zoom_button']) ? true : false;
		$link_button = !isset($products_settings['disable_link_button']) ? true : false;
		
		echo (!$zoom_button && !$link_button) ? '<a href="'. esc_attr(get_permalink()).'" title="'. esc_attr(strip_tags(get_the_title())).'">' : '';
		?>
		
		<div class="item-img">
		
		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
			
			do_action( 'woocommerce_before_shop_loop_item_title' );
		?>

		</div>
		
		<?php echo (!$zoom_button && !$link_button) ? '</a>' : ''; ?>
	
		<div class="item-data">
				
			<div class="table">
			
				<div class="tablerow">	
				
				<?php
				if( defined('WPML_ON') ) { // if WPML plugin is active
					$id			= icl_object_id( get_the_ID(), 'product', false, ICL_LANGUAGE_CODE ); 
					$lang_code	= ICL_LANGUAGE_CODE;
				}else{
					$id			= get_the_ID();
					$lang_code	= '';
				}
				$buttons = apply_filters( 'sequoia_options', 'catalog_buttons', array( 'shop_quick' => true ,'shop_buy_action' => true,'shop_wishlist' => true ) );
		
				if( $buttons['shop_quick'] ) {
					echo '<div class="item-buttons-holder tablecell">';
						echo '<a href="#qv-holder" class="quick-view tip-top"   title="'.__('Quick view','sequoia').' - '. esc_attr(strip_tags(get_the_title())) .'" data-id="'.$id.'" data-lang="'. $lang_code .'" data-tooltip><span class="icon-eye"></span></a>'; // Quick view button
					echo '</div>'; // tablecell
					
					if ( !wp_script_is( 'wc-add-to-cart-variation', 'enqueued' )) {
					
						wp_register_script( 'wc-add-to-cart-variation', WP_PLUGIN_DIR . '/woocommerce/assets/frontend/add-to-cart-variation.min.js');
						wp_enqueue_script( 'wc-add-to-cart-variation' );
						
					}
				
				}
				
				if( $buttons['shop_buy_action'] ) {
					
					echo '<div class="item-buttons-holder tablecell">';
					
						do_action( 'woocommerce_after_shop_loop_item' ); // "Add to cart button
						
					echo '</div>'; // tablecell
					
				}
				
				if( $buttons['shop_wishlist'] && $sequoia_wishlist_is_active ) {
					echo '<div class="item-buttons-holder tablecell">';
						do_action('as_wishlist_button'); // Wishlist button
					echo '</div>'; // tablecell
				}
				//
				?>
			
				</div>
			

			</div>
						
			<?php 

			$no_buttons =( !$buttons['shop_quick'] && !$buttons['shop_buy_action'] && !$buttons['shop_wishlist'] ) ?  true : false;
	
			
			echo $no_buttons ? '<div class="no-buttons">' : null;
			?>
			
			<h3 class="prod-title"><a href="<?php echo esc_attr(get_permalink()); ?>" title="<?php echo esc_attr(get_the_title()); ?>"> <?php echo esc_attr(get_the_title()); ?> </a></h3>
			
			<?php woocommerce_template_loop_price(); ?>
		
			<?php echo $no_buttons ? '</div>' : null; ?>
		
		</div><!-- .item-data -->

	
	<div class="clearfix"></div>


</li>
<?php ?>