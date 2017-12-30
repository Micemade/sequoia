<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?> | <?php bloginfo( 'description' );?>" rel="home" class="home-link">
	<h1>

		<?php
		$logo		= apply_filters( 'sequoia_options', 'site_logo', get_stylesheet_directory_uri().'/img/logo.png' );
		$logo_desc	= apply_filters( 'sequoia_options', 'logo_desc', array('logo_on') );
		$logo_on	= !empty ( $logo_desc['logo_on'] );
		$desc_on	= !empty ( $logo_desc['desc_on'] );
		if ( $logo_on &&  $logo  ) {
		?>
			<img src="<?php echo $logo ; ?>" title="<?php bloginfo( 'name' ); ?> | <?php bloginfo( 'description' );?>" alt="<?php bloginfo( 'name' ); ?>" />
		
		<?php } else { ?>
		
			<span><?php bloginfo( 'name' ); ?></span>
			
		<?php } ?>

	</h1>

</a>

<?php if ( $desc_on ) { ?>
	<div id="site-description"><?php bloginfo( 'description' ); ?></div>
<?php } ?>