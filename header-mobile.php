<?php
/**
 *	Template part: Header mobile 
 *
 *	template for mobile devices - logo, main menu etc.
 */
global $sequoia_woo_is_active, $border_decor;
?>

<div class="row clearfix">

	<div id="site-title-mobile" class="small-12">
				
		<?php get_template_part('template_parts/logo_desc'); ?>
		
	</div>

	<div class="mobile-sticky">
	
	
	<div class="menu-toggler">
		<a href="#" title="<?php echo __('Toggle menu','sequoia') ;?>" class="button tiny">
			<span class="icon-menu-2"></span>
		</a>
		<div class="clearfix"></div>
	</div>

	<div class="mobile-dropdown">
	
		<?php
		$default_blocks = apply_filters('sequoia_mobile_header_blocks','');
		$mobile_header_blocks = apply_filters( 'sequoia_options', 'mobile_header_blocks', $default_blocks );
		if( isset( $mobile_header_blocks['enabled'] ) ) {
		
			$headblocks = $mobile_header_blocks['enabled'];
			
			foreach ( $headblocks as $block ) {
			
				$block_array_check =  strpos( $block, "|");
				// if are saved as resizable
				if( $block_array_check ) {
				
					$bl =  explode("|", $block ); // $bl[0] - block name, $bl[1] - block width
					
					switch ( $bl[0] ) {
					
						//////////////////////////////////////////
						case 'Shopping cart' :
						
						/**
						 *	IF WOOCOMMERCE is ACTIVATED
						 *
						 */
						if ( $sequoia_woo_is_active ) {
						
							echo '<div class="wrap-mini-cart-mobile">';
							
							$cart_count = WC()->cart->cart_contents_count;
							$cart_link = get_permalink( wc_get_page_id( 'cart' ));
							$cart_action = apply_filters( 'sequoia_options', 'cart_action', 'popup' );
						
							echo ($cart_action == 'page') ? '<a href="'. $cart_link .'" class="header-cart-sequoia">' : '<div class="header-cart-sequoia mini-cart-toggle">';
							?>
									
								<span class="icon-cart-2 mini-cart-icon" aria-hidden="true"></span>
								
								<span class="cart-contents">
								
								<?php echo '<span class="count button round">'.$cart_count.'</span>'; ?>
								
								<?php echo WC()->cart->get_cart_total(); ?>
								
								</span>
								
								<div class="clearfix"></div>

							<?php echo ($cart_action == 'page') ? '</a>' : '</div>';

							echo '<div class="mini-cart-list"><span class="arrow-up"></span><div class="widget_shopping_cart_content">';
								
								wc_get_template_part('mini','cart');
								
							echo '</div></div>'; //end mini cart

							echo '</div>'; // end wrap-mini-cart
						
						} // endif $sequoia_woo_is_active
						
						break;
						//////////////////////////////////////////
						
						case 'Menu mobile' :
						?>
						<nav id="main-nav-wrapper-mobile" class="small-12">
							
							<?php 
							$walker = new My_Walker;
							wp_nav_menu( array( 
									'theme_location'	=> 'main-mobile',
									//'menu'			=> 'Main menu',
									'walker'			=> $walker,
									'link_before'		=> '',
									'link_after'		=> '',
									'menu_id'			=> 'main-nav-mobile',
									'menu_class'		=> 'navigation ',
									'container'			=> false
									) 
								);
							?>
							
						</nav>
						<div class="clearfix"></div>
						
						<?php 
						break;
						//////////////////////////////////////////
						case 'Search' :
						
							if( $sequoia_woo_is_active ) {
								as_get_product_search_form();
							}else{
								get_template_part('searchform','menu');
							}
						
						break;
						
	
						//////////////////////////////////////////
						case 'Widgets block' :
						
						if ( is_active_sidebar( 'sidebar-header' ) ) {
						
							dynamic_sidebar( 'sidebar-header' ); 
							
						}
						
						break;
						//////////////////////////////////////////
						case 'Widgets block 2' :
						
						if ( is_active_sidebar( 'sidebar-header-2' ) ) {
							
							dynamic_sidebar( 'sidebar-header-2' ); 
							
						}
						
						break;
						//////////////////////////////////////////
						case 'Widgets block 3' :
						
						if ( is_active_sidebar( 'sidebar-header-3' ) ) {
							
							dynamic_sidebar( 'sidebar-header-3' ); 
							
						}
						
						break;
						}
				}
			}
		
		}
		?>
	</div>
	
	</div>
	
	
</div>