<?php
/**
 *	Template part: Header Vertical 
 *	vertical customizable template for header - float left with fixed position
 */
global $sequoia_woo_is_active;
?>

<header id="site-menu" class="vertical">
		
	<div class="row clearfix">

	<?php 
	$defaults = apply_filters('sequoia_vertical_header_blocks','');
	$default_header_blocks = apply_filters( 'sequoia_options', 'default_header_blocks', $defaults );
	if( isset( $default_header_blocks['enabled'] ) ) {
	
		$headblocks = $default_header_blocks['enabled'];
		
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
					
						global $woocommerce;
						
						echo '<div class="wrap-mini-cart">';

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
					case 'Site title or logo' :
					?>
					<div id="site-title" class="small-12">
			
						<?php get_template_part('template_parts/logo_desc'); ?>
						
					</div>
					
					<?php 
					
					break;
					//////////////////////////////////////////
					case 'Menu' :
					?>
					<nav id="main-nav-wrapper" class="small-12">
						
						<?php 
						$walker = new My_Walker;
						wp_nav_menu( array( 
								'theme_location'	=> 'main-vertical',
								//'menu'			=> 'Main menu',
								'walker'			=> $walker,
								'link_before'		=> '',
								'link_after'		=> '',
								'menu_id'			=> '',
								'menu_class'		=> 'navigation main-nav',
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
					
						if ( $sequoia_woo_is_active && defined( 'YITH_WCAS' ) ) {
				
							echo as_yith_ajax_search();
							
						}elseif( $sequoia_woo_is_active ) {
							as_get_product_search_form();
						}else{
							get_template_part('searchform','menu');
						}
							
					break;
					//////////////////////////////////////////
					
					case 'Contact / Social' :
					
	
						$info_items = apply_filters( 'sequoia_options', 'topbar_info', array() );
						if( count($info_items) ) {
							echo '<ul class="topbar-info">';
							foreach ( $info_items as $item ) {
								
								$toggle = !empty($item['toggle']) ? ' toggle' : null;
								
								
								echo '<li class="topbar-info-item">';
								
								echo $item['link']	? '<a href="'.esc_url($item['link']).'" title="'.$item['title'].'" target="_blank">' : null;
								echo $item['icon']	? '<span class="'.$item['icon']. ' icon'. $toggle .'"></span>' : null;
								echo $item['title']	? '<span class="title'.$toggle.'">'.$item['title'].'</span>' : null;
								echo $item['link']	? '</a>' : null;
								
								echo '</li>';	
								
							}
							echo '</ul>';
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
	
	</div><!-- .row -->
	
</header>

<div id="site-menu-mobile">

	<?php get_template_part('header','mobile'); ?>

</div>