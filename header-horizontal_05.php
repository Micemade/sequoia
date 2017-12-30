<?php
/**
 *	Template part: Header Horizontal  05
 *	horizontal template for header
 */
global $of_sequoia, $sequoia_woo_is_active;
?>

<header id="site-menu" class="horizontal header-template-05">

	<div class="stick-it-header"><div class="stick-inner row"></div></div>
	
	<div class="topbar">
	
		<div class="row clearfix">
			
			<div class="large-6 columns">
			
				<?php get_template_part('secondary_menu'); ?>
							
			</div>
			
			<div class="large-6 columns">
			
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
			?>
			
			</div>
			
			<div class="large-12 columns">
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
	
	</div>
	
	
	<span class="topbar-toggle icon-arrow-down-5"></span>
	
	
	<div class="row clearfix sub-mega-holder" style="padding: 1rem 0;" data-equalizer>

		<div id="site-title" class="large-3 columns vertical-align" data-equalizer-watch>
				
			<div class="vertical-middle">
			
				<?php get_template_part('template_parts/logo_desc'); ?>
			
			</div>
			
		</div>
	

		<div class="large-9 columns vertical-align" style="position: relative;"  data-equalizer-watch>
			
			<div class="vertical-middle">
				
				<nav id="main-nav-wrapper" class="large-10 columns">
										
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
				
				<?php 
				if ( $sequoia_woo_is_active ) { 
				
				echo '<div class="wrap-mini-cart large-2 columns">';
				
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

				<div class="clearfix"></div>

			
			</div><!-- .vertical-middle -->
		
		</div>
		
		
		<div class="clearfix"></div>

	</div>
	
	
	<div class="clearfix"></div>
	
	<div class="row">
	
		<div class="large-12 column breadcrumbs-holder"><?php get_template_part('breadcrumbs'); ?></div>
	
	</div>
	
	
</header>

<div id="site-menu-mobile">

	<?php get_template_part('header','mobile'); ?>

</div>