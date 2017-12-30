<?php
/**
 *	Template part: Header Horizontal  03
 *	horizontal template for header
 */
global $sequoia_woo_is_active ;
?>

<header id="site-menu" class="horizontal header-template-03">
	
	<div class="stick-it-header"><div class="stick-inner row"></div></div>
	
	<div class="row sub-mega-holder">
	
		<nav id="main-nav-wrapper" class="large-12">
									
			<?php 
			$walker = new My_Walker;
			wp_nav_menu( array( 
					'theme_location'	=> 'main-horizontal',
					//'menu'			=> 'Main menu',
					'walker'			=> $walker,
					'link_before'		=> '',
					'link_after'		=> '',
					'menu_id'			=> '',
					'menu_class'		=> 'navigation to-stick horizontal main-nav',
					'container'			=> false
					) 
				);
			?>
			
		</nav>

	</div>
	

	
	<div class="row clearfix" data-equalizer>
		
		<div class="large-4 columns vertical-align" data-equalizer-watch>
			
			<div class="vertical-middle">
			
				<?php get_template_part('secondary_menu'); ?>
				
				<?php
				
				if ( $sequoia_woo_is_active ) { 
				
					echo '<div class="wrap-mini-cart large-3">';

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
				?>
			
			
			</div>
			
			
		</div>
		
				
		<div id="site-title" class="large-4 columns vertical-align" data-equalizer-watch>
				
			<div class="vertical-middle">
			
				<?php get_template_part('template_parts/logo_desc'); ?>
			
			</div>
		
		</div>
		
		
		<div class="large-4 columns vertical-align" data-equalizer-watch>
		
			<div class="vertical-middle">
			
			<?php
			if ( $sequoia_woo_is_active && defined( 'YITH_WCAS' ) ) {
			
				echo as_yith_ajax_search();
				
			}elseif( $sequoia_woo_is_active ) {
				as_get_product_search_form();
			}else{
				get_template_part('searchform','menu');
			}
			?>
			
			</div>
		
		</div>
		
		<div class="clearfix"></div>
		
	</div><!-- .row -->
	
	<div class="topbar">
	
		<div class="row clearfix">
			
			<div class="large-12 columns" style="text-align: center">
			
			<?php 
			
			$info_items = apply_filters( 'sequoia_options', 'topbar_info', array() );
			if( count($info_items) ) {
				echo '<div class="topbar-info">';
				foreach ( $info_items as $item ) {
					
					$toggle = !empty($item['toggle']) ? ' toggle' : null;
					
					echo $item['link'] ? '<a href="'.esc_url($item['link']).'" title="'.$item['title'].'" target="_blank">' : null;
					echo '<div class="topbar-info-item">';
					
					echo $item['icon'] ? '<span class="'.$item['icon']. ' icon'. $toggle .'"></span>' : null;
					echo $item['title'] ? '<span class="title'.$toggle.'">'.$item['title'].'</span>' : null;
					
					echo '</div>';
					
					
					echo $item['link'] ? '</a>' : null;
				}
				echo '</div>';
			}
			
			//get_template_part('social'); 
			?>
			
			</div>
			
		</div>
	
	</div><!-- .topbar -->
	
	
	<?php
	/* if ( !wp_script_is( 'foundation-equalizer', 'enqueued' )) {
		wp_register_script( 'foundation-equalizer', get_template_directory_uri() . '/js/foundation/foundation.equalizer.js');
		wp_enqueue_script( 'foundation-equalizer' );
	} */
	?>
	
	
	<div class="clearfix"></div>
	
	<div class="row">
	
		<div class="large-12 column breadcrumbs-holder"><?php get_template_part('breadcrumbs'); ?></div>
	
	</div>	
	
</header>

<div id="site-menu-mobile">

	<?php get_template_part('header','mobile'); ?>

</div>