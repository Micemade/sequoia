<?php
/**
 *	Template part: Header Horizontal  04
 *	horizontal template for header
 */
global $sequoia_woo_is_active;

$anim = apply_filters( 'sequoia_options', 'min_header_anim', '1' );

wp_register_style( 'sidebar-transitions', get_template_directory_uri() .'/css/component.css' );
wp_enqueue_style ( 'sidebar-transitions' );
wp_register_script('sidebarEffects', get_template_directory_uri().'/js/sidebarEffects.js');
wp_enqueue_script ('sidebarEffects', get_template_directory_uri().'/js/sidebarEffects.js', array('jQuery'), '1.0', true);
?>


<?php if( $anim == '3' || $anim == '6' || $anim == '7' || $anim == '8' || $anim == '14' ) {
	echo '<div class="st-pusher">';
}
?>
<nav class="st-menu st-effect-<?php echo $anim ?>" id="menu-<?php echo $anim ?>">

	<div class="clearfix"></div>
	
	
	<nav id="main-nav-wrapper" class="large-12 column side-subs">
	<?php 
	$walker = new My_Walker;
	wp_nav_menu( array( 
			'theme_location'	=> 'main-horizontal',
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
	
	<div class="small-12 column">
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
			
	<div class="large-12 column">
	
		<?php get_template_part('secondary_menu'); ?>
					
	</div>
	
	<div class="large-12 column">
	
	<?php 
	
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
	?>
	</div>	
	
</nav>


<?php if( $anim == '1' || $anim == '2' || $anim == '4' || $anim == '5' || $anim == '9' || $anim == '10' || $anim == '11' || $anim == '12' || $anim == '13' ) {
	echo '<div class="st-pusher">';
}
?>



<div class="st-content">

<header id="site-menu" class="horizontal header-template-simple no-clones">
	
	
	<div class="row clearfix" data-equalizer>

		<div class="small-1 columns" style="position: relative;"  data-equalizer-watch>
			
			<div class="vertical-align"><div class="vertical-middle" id="st-trigger-effects">
			
				<a data-effect="st-effect-<?php echo $anim ?>" class="icon-menu-2 sidemenu-toggler to-stick" href=""></a>
			
			</div></div><!-- .vertical-middle --><!-- .vertical-align -->
		
		</div>
		
		
		<div id="site-title" class="small-9 columns" data-equalizer-watch>
				
			<div class="vertical-align"><div class="vertical-middle">
			
				<?php get_template_part('template_parts/logo_desc'); ?>
		
			</div></div><!-- .vertical-middle --><!-- .vertical-align -->
			
		</div>

		
		<?php 
		if ( $sequoia_woo_is_active ) { 
		
			echo '<div class="small-2 column" data-equalizer-watch>';
			echo '<div class="vertical-align"><div class="wrap-mini-cart vertical-middle">';
			
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
			
			echo '</div></div></div>'; // end wrap-mini-cart // end vertical-align // small-2
		 
		} // endif $sequoia_woo_is_active 
		?>
		
		
		<div class="clearfix"></div>

	</div>
	
	
	<div class="clearfix"></div>
	
	<div class="row">
	
		<div class="large-12 column breadcrumbs-holder"><?php get_template_part('breadcrumbs'); ?></div>
	
	</div>		
	

</header>