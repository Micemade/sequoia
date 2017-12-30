<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
// 3.0.0 < Fallback conditional :
$product_id	= apply_filters( 'sequoia_wc_version', '3.0.0'  ) ? $product->get_id() : $product->id;
?>

<?php

/**
 *	REPLACE SHOP SINGLE SIZE VALUES WITH THEME REGISTERED SIZE VALUES
 *
 */

$img_sizes = all_image_sizes();
$of_imgformat = apply_filters( 'sequoia_options', 'single_product_image_format',  'as-portrait' );
if( $of_imgformat !== 'plugin' ){
	
	$img_width	= $img_sizes[$of_imgformat]['width'];
	$img_height = $img_sizes[$of_imgformat]['height'];

	$shop_single_w = $img_sizes['shop_single']['width'];
	$shop_single_h = $img_sizes['shop_single']['height'];
	
	
	$to_replace = $shop_single_w.'x'.$shop_single_h;
	$replace_size = $img_width .'x'. $img_height;

	if( $available_variations != false ) {
		foreach($available_variations as $var ){
			
			if( apply_filters( 'sequoia_wc_version', '3.0.0' ) ) {
				$img_src = str_replace( $to_replace, $replace_size , $var['image']['src'] );
				$var['image']['src'] = $img_src;
				$variations_changed[] = $var;
			}else{
				$img_src = str_replace( $to_replace, $replace_size , $var['image_src'] );
				$replacements = array ( 'image_src' => $img_src );
				$variations_changed[] = array_replace( $var, $replacements );
			}
			
		}
		$available_variations = $variations_changed;		
	}

}
$attribute_keys = array_keys( $attributes );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product_id ); ?>" data-product_variations="<?php echo htmlspecialchars( json_encode( $available_variations ) ) ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'sequoia' ); ?></p>
	<?php else : ?>
		<table class="variations" cellspacing="0">
			<tbody>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
					<tr>
						<td class="label"><label for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?></label></td>
						<td class="value">
							<?php
								$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) : $product->get_variation_default_attribute( $attribute_name );
								wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected ) );
								echo end( $attribute_keys ) === $attribute_name ? apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . __( 'Clear', 'sequoia' ) . '</a>' ) : '';
							?>
						</td>
					</tr>
		        <?php endforeach;?>
			</tbody>
		</table>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<div class="single_variation_wrap">
			<?php
				/**
				 * woocommerce_before_single_variation Hook.
				 */
				do_action( 'woocommerce_before_single_variation' );

				/**
				 * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				do_action( 'woocommerce_single_variation' );

				/**
				 * woocommerce_after_single_variation Hook.
				 */
				do_action( 'woocommerce_after_single_variation' );
			?>
		</div>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
