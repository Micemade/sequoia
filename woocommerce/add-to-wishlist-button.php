<?php
/**
 * Add to wishlist button template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.0
 */

global $product ;
// 3.0.0 < Fallback conditional :
$product_id	= apply_filters( 'sequoia_wc_version', '3.0.0'  ) ? $product->get_id() : $product->id;
$icon 		= '<span class="icon-heart-2"></span>';
$classes	= 'add_to_wishlist tip-top';
$title_add	= __('Add to wishlist','sequoia');
$product_type = $product->get_type();
?>

<a href="<?php echo esc_url( add_query_arg( 'add_to_wishlist', esc_attr($product_id) ) )?>" data-product-id="<?php echo esc_attr( $product_id	); ?>" data-product-type="<?php echo $product_type ?>" class="<?php echo $classes ?>" title="<?php  echo $title_add; ?>" data-tooltip>
    <?php echo $icon ?> 
</a>
<img src="<?php echo esc_url( get_template_directory_uri(). '/img/ajax-loader.gif' )  ?>" class="ajax-loading" alt="loading" width="16" height="16" style="visibility:hidden" />