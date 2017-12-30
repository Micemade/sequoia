<?php
/*-----------------------------------------------------------------------------------*/
/* Head Hook
/*-----------------------------------------------------------------------------------*/

function of_head() { do_action( 'of_head' ); }

/*-----------------------------------------------------------------------------------*/
/* Add default options after activation */
/*-----------------------------------------------------------------------------------*/
if ( is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {
	//Call action that sets
	add_action('admin_head','of_option_setup');
}

/* set options=defaults if DB entry does not exist, else update defaults only */
function of_option_setup()	{
	
	global $of_options, $options_machine;
	
	$options_machine = new Options_Machine($of_options);
		
	if (!get_option(OPTIONS)){
		
		$defaults = (array) $options_machine->Defaults;
		update_option(OPTIONS,$defaults);
		generate_options_css($defaults); 
		
	}
	
	
}

/*-----------------------------------------------------------------------------------*/
/* Admin Backend */
/*-----------------------------------------------------------------------------------*/
function optionsframework_admin_message() { 
	
	//Tweaked the message on theme activate
	?>
    <script type="text/javascript">
    jQuery(function(){
    	
        var message = '<p>This theme comes with an <a href="<?php echo admin_url('admin.php?page=optionsframework'); ?>">options panel</a> to configure settings. This theme also supports widgets, please visit the <a href="<?php echo admin_url('widgets.php'); ?>">widgets settings page</a> to configure them.</p>';
    	jQuery('.themes-php #message2').html(message);
    
    });
    </script>
    <?php
	
}

add_action('admin_head', 'optionsframework_admin_message'); 

/*-----------------------------------------------------------------------------------*/
/* Generate css from options */
/*-----------------------------------------------------------------------------------*/
function generate_options_css($newdata) { 

	global $of_sequoia;
	
	$of_sequoia = $newdata;
		
	$uploads_dir = wp_upload_dir();
	
	$theme_data = wp_get_theme(); // get theme info
	$theme 		= sanitize_title( $theme_data ); // make Sequoia Fashion to be sequoia-fashion
	
	
	$as_upload_folder		= trailingslashit( $uploads_dir['basedir'] ) .$theme.'-options'; // "wp-content/uploads" folder
	$theme_options_folder	= trailingslashit( get_stylesheet_directory() ) . 'admin_save_options/'; // theme folder
	
	
	// CREATE OPTIONS CSS
	ob_start(); // Capture all output (output buffering)
	require( $theme_options_folder . 'theme_options_styles.php' ); // Generate CSS
	$css = ob_get_clean(); // Get generated CSS (output buffering)

	// CREATE DYNAMIC JS
	ob_start(); // Capture all output (output buffering)
	require( $theme_options_folder . 'theme_options_js.php' ); // Generate JS
	$js = ob_get_clean(); // Get generated JS (output buffering)


	// CREATE ADMIN LOGIN CSS
	ob_start(); // Capture all output (output buffering)
	require( $theme_options_folder . 'custom_login_css.php' ); // Generate JS
	$cl_css = ob_get_clean(); // Get generated JS (output buffering)

	
	
	/** 
	 *	CREATE FOLDER IN "WP-CONTENT/UPLOADS" TO WRITE 
	 *	Write to theme_options_styles.css and theme_options_js.js file
	 *
	 */ 
	
	WP_Filesystem();
	global $wp_filesystem;
	
	// if no Sequoia theme options dir in "uploads" folder, create one
	$target_dir = $wp_filesystem->is_dir( $as_upload_folder );
	if( !$target_dir ) {
		
		$wp_filesystem->mkdir($as_upload_folder, FS_CHMOD_DIR);

	}
	
	
	$as_upload_dir_exists = is_dir( $as_upload_folder );
	
	if( $as_upload_dir_exists ) {
		// create files in "SEQUOIA_THEME_OPTIONS" wp-content/uploads dir
		if ( ! $wp_filesystem->put_contents( $as_upload_folder . '/theme_options_styles.css', $css, 0644 ) ) {
			return true;
		}

		if ( ! $wp_filesystem->put_contents( $as_upload_folder . '/theme_options_js.js', $js, 0644 ) ) {
			return true;
		}
		
		if ( ! $wp_filesystem->put_contents( $as_upload_folder . '/custom_login_css.css', $cl_css, 0644 ) ) {
			return true;
		}
		
	}else{
		// create files in "ADMIN_SAVE_OPTIONS" theme dir
		if ( ! $wp_filesystem->put_contents( $theme_options_folder . '/theme_options_styles.css', $css, 0644 ) ) {
			return true;
		}

		if ( ! $wp_filesystem->put_contents( $theme_options_folder . '/theme_options_js.js', $js, 0644 ) ) {
			return true;
		}
		
		if ( ! $wp_filesystem->put_contents( $theme_options_folder . '/custom_login_css.css', $cl_css, 0644 ) ) {
			return true;
		}
		
	}
	
}

/*-----------------------------------------------------------------------------------*/
/* Small function to get all header classes */
/*-----------------------------------------------------------------------------------*/

function of_get_header_classes_array() {
	
	global $of_options;
	
	foreach ($of_options as $value) {
		
		if ($value['type'] == 'heading') {
			$hooks[] = preg_replace("/[^a-zA-Z0-9._\-]/", "", strtolower($value['name']) );
		}
		
	}
	return $hooks;
}
/*-----------------------------------------------------------------------------------*/
/* FOR USE IN THEME: */
/*-----------------------------------------------------------------------------------*/

$of_sequoia = get_option( OPTIONS );

if ( !isset($smof_details) )
	$smof_details = array();
?>