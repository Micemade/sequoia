<?php
/**
 * WooCommerce Addon Plugins
 * 
 * @since 1.5.0
 * @package WordPress
 * @subpackage Sequoia
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// AS WISHLIST.
// extending and modifying YITH WISHLIST plugin ( plugin must be installed and activated ).
if ( class_exists( 'YITH_WCWL_UI' ) ) {
	// FOR PB BLOCKS, CATALOG etc.
	add_action( 'as_wishlist_button', 'as_wishlist_button_func', 10 );

	function as_wishlist_button_func() {
		yith_wcwl_get_template( 'add-to-wishlist.php' );
	}

	function dequeue_yith_styles() {
		wp_dequeue_style( 'yith-wcwl-font-awesome' );
		wp_dequeue_style( 'yith-wcwl-font-awesome-ie7' );

	}
	add_action( 'wp_enqueue_scripts', 'dequeue_yith_styles' );

}
//end YITH WISHLIST related functions.

/**
 * AS YITH AJAX SEARCH
 * 
 * 
 * @return echo
 */
if ( ! function_exists( 'as_yith_ajax_search' ) ) {

	function as_yith_ajax_search() {

		if ( !defined( 'YITH_WCAS' ) ) {
			return;
		}
		wp_enqueue_script( 'yith_wcas_jquery-autocomplete' );
		?>

		<div class="yith-ajaxsearchform-container searchform-menu">
		<form role="search" method="get" id="yith-ajaxsearchform" action="<?php echo esc_url( home_url( '/' ) ) ?>">
			<div>

				<?php
				$label     = get_option( 'yith_wcas_search_input_label' );
				$placehold = $label ? $label : esc_attr__( 'Search for products', 'sequoia' );
				?>

				<input type="search"
					value="<?php echo esc_attr( get_search_query() ); ?>"
					name="s"
					id="yith-s"
					class="yith-s"
					placeholder="<?php echo esc_attr( $placehold ); ?>"
					data-loader-icon="<?php echo esc_url( get_template_directory_uri() . '/img/ajax-loader.gif' ); ?>"
					data-min-chars="<?php echo esc_attr( get_option( 'yith_wcas_min_chars' ) ); ?>" />

				<button type="submit" class="icon-search"></button>

				<input type="hidden" name="post_type" value="product" />
				<?php if ( defined( 'ICL_LANGUAGE_CODE' ) ) { ?>
					<input type="hidden" name="lang" value="<?php echo esc_attr( ICL_LANGUAGE_CODE ); ?>" />
				<?php } ?>
			</div>
		</form>
		</div>
		<script type="text/javascript">

		jQuery(document).ready(function ($) {
			"use strict";

			var el = $('.yith-s'),
				loader_icon = el.data('loader-icon') == '' ? '' : el.data('loader-icon'),
				search_button = $('#yith-searchsubmit'),
				min_chars = el.data('min-chars');

			search_button.on('click', function(){
				var form = $(this).closest('form');
				if( form.find('.yith-s').val()==''){
					return false;
				}
				return true;
			});

			if( el.length == 0 ) el = $('#yith-s');

			el.each(function () {
				var $t = $(this),
					append_to = ( typeof  $t.data('append-to') == 'undefined') ? $t.closest('.yith-ajaxsearchform-container') : $t.data('append-to');

				el.yithautocomplete({
					minChars        : min_chars,
					appendTo        : append_to,
					serviceUrl      : woocommerce_params.ajax_url + '?action=yith_ajax_search_products',
					onSearchStart   : function () {
						$(this).css('background', 'url(' + loader_icon + ') no-repeat right center');
					},
					onSelect        : function (suggestion) {
						if (suggestion.id != -1) {
							window.location.href = suggestion.url;
						}
					}  ,
					onSearchComplete: function () {
						$t.css('background', 'transparent');
					}
				});
			});
		});
		</script>
	<?php
	}
	// end function as_yith_ajax_search.
}
// end if function_exists.