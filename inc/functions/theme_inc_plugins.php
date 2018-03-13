<?php
/**
 * This file is a configuration for importing required and recommended plugins.
 *
 * @package	   TGM-Plugin-Activation
 * @subpackage Theme plugins
 * @version	   2.5.2
 * @author	   Thomas Griffin <thomas@thomasgriffinmedia.com>
 * @author	   Gary Jones <gamajo@gamajo.com>
 * @copyright  Copyright (c) 2012, Thomas Griffin
 * @license	   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 */

require_once trailingslashit( get_template_directory() ) . 'inc/tgm-plugin-activation/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'sequoia_micemade_register_required_plugins' );
/**
 * Register the required plugins for sequoia theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function sequoia_micemade_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		// REQUIRED PLUGINS
		array(
			'name' 				=> 'GitHub Updater',
			'slug' 				=> 'github-updater',
			'source'			=> get_template_directory() . '/inc/plugins/github-updater.zip',
			'required' 			=> true,
			'force_activation' 	=> true,
			'force_deactivation'=> false,
		),
		array(
			'name' 				=> 'Sequoia Additional Functions',
			'slug' 				=> 'sequoia-additional-functions',
			'source'			=> 'https://demo.micemade.com/tgmpa/sequoia-additional-functions.zip',
			'external_url'		=> 'https://demo.micemade.com/tgmpa/sequoia-additional-functions.zip',
			'required' 			=> true,
		),
		array(
			'name' 				=> 'WooCommerce',
			'slug' 				=> 'woocommerce',
			'required' 			=> true,
		),
		array(
			'name'     			=> 'Aqua Page Builder',
			'slug'     			=> 'aqua-page-builder',
			'required' 			=> true,
			'version' 			=> '1.1.5',
			'force_activation' 	=> false, 
			'force_deactivation' => false
		),	 
		array(
			'name' 				=> 'Revolution Slider',
			'slug' 				=> 'revslider',
			'source'			=> 'https://demo.micemade.com/tgmpa/revslider.zip',
			'external_url'		=> 'https://demo.micemade.com/tgmpa/revslider.zip',
			'required' 			=> true,
		),
		array(
			'name' 				=> 'WP Envato Market',
			'slug' 				=> 'envato-market',
			'source'			=> 'http://envato.github.io/wp-envato-market/dist/envato-market.zip',
			'required' 			=> true,
		),
		
		//	Recommended plugins
		//	
		//
		array(
			'name' 				=> 'AS Shortcodes',
			'slug' 				=> 'as-shortcodes',
			'source'			=> 'https://demo.micemade.com/tgmpa/as-shortcodes.zip',
			'external_url'		=> 'https://demo.micemade.com/tgmpa/as-shortcodes.zip',
			'required' 			=> false,

		),
		array(
			'name' 				=> 'Attachment importer',
			'slug' 				=> 'attachment-importer',
			'required' 			=> false,
		),
		array(
			'name' 				=> 'YITH WooCommerce Wishlist',
			'slug' 				=> 'yith-woocommerce-wishlist',
			'required' 			=> false,
		),
		array(
			'name' 				=> 'YITH WooCommerce Ajax Search',
			'slug' 				=> 'yith-woocommerce-ajax-search',
			'required' 			=> false,
		),
		array(
			'name' 				=> 'WooCommerce ShareThis Integration',
			'slug' 				=> 'woocommerce-sharethis-integration',
			'required' 			=> false
		),

	);
	
	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
        'default_path' => '',						// Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins',	// Menu slug.
        'has_notices'  => true,						// Show admin notices or not.
        'dismissable'  => true,						// If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',						// If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => true,						// Automatically activate plugins after installation or not.
        'message'      => '',						// Message to output right before the plugins table.
        'strings'      => array(
            'page_title'                      => esc_html__( 'Install Required Plugins', 'sequoia' ),
            'menu_title'                      => esc_html__( 'Install Plugins', 'sequoia' ),
            'installing'                      => esc_html__( 'Installing Plugin: %s', 'sequoia' ),
            'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'sequoia' ),
            'notice_can_install_required'     => _n_noop( 'Sequoia theme requires the following plugin: %1$s.', 'Sequoia theme requires the following plugins: %1$s.', 'sequoia' ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop( 'Sequoia theme recommends the following plugin: %1$s.', 'Sequoia theme recommends the following plugins: %1$s.', 'sequoia' ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' , 'sequoia'), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'sequoia' ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'sequoia' ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'sequoia' ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with sequoia theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with sequoia theme: %1$s.' , 'sequoia'), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'sequoia' ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'sequoia' ),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'sequoia' ),
            'return'                          => esc_html__( 'Return to Required Plugins Installer', 'sequoia' ),
            'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'sequoia' ),
            'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'sequoia' ), // %s = dashboard link.
            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        )
    );
	tgmpa( $plugins, $config );

}