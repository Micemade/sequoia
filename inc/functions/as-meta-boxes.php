<?php
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function as_meta_boxes( array $meta_boxes ) {
	
	global $sequoia_woo_is_active;
	
	$prefix = 'as_';
	
	// general settings meta boxes:
	$general_fields = array(
		
		array(
			'name'	=> __('Hide featured image','sequoia'),
			'desc'	=> 'remove featured image OR images attached to post from archives, taxonomy page and single pages header).',
			'id'	=> $prefix.'hide_featured_image',
			'type'  => 'checkbox',
		),
		array(
			'name'	=> __('Hide post title in archives','sequoia'),
			'desc'	=> 'hide post title when post is listed in archives ( useful when using Image or Quote post formats, for example) ',
			'id'	=> $prefix.'hide_archive_titles',
			'type'	=> 'checkbox',
		),
		array(
			'name'	=> __('Hide page title','sequoia'),
			'desc'  => __('applies only to pages not singular posts','sequoia'),
			'id'    => $prefix.'hide_title',
			'type'  => 'checkbox',
		),
		array(
			'name'	=> __('Page under header','sequoia'),
			'desc'  => __('draw page content "under" page header (use with caution)','sequoia'),
			'id'    => $prefix.'page_under_head',
			'type'  => 'checkbox',
		)
	);
	
	$meta_boxes[] = array(
		'title'		=> 'General settings',
		'pages'		=> array('post','page'),
		'fields'	=> $general_fields,
		'context'	=> 'side',
		'priority'	=> 'high'
	);
	
	
	
	// POST FORMAT MENU
	// add post format tabs menu first:
	$meta_boxes[] = array( 
		'title'		=> 'Post format tabs', 
		'fields'	=> array(),
		'pages'		=> array('page','post','portfolio'),
		'context'	=> 'normal',
		'priority'	=> 'high' 
	);
	
	// POST FORMAT meta boxes - gallery:
	$format_gallery_fields = array(
		
		array(
			'name'			=> __('Upload images','sequoia'), 
			'id'			=> $prefix.'gallery_images',
			'type'			=> 'image',
			'repeatable'	=> true,
			'sortable'		=> true,
			'show_size' 	=> true 
		),
		array(
			'name'		=> __('Gallery image format','sequoia'),
			'desc'		=> __('choose the format for image display in gallery.','sequoia'),
			'id'		=> $prefix.'gall_image_format',
			'type'		=> 'select',
			'options'	=> array(
				
				'thumbnail'		=> __('Thumbnail','sequoia'),
				'medium'		=> __('Medium','sequoia'),
				'as-portrait'	=> __('Sequoia portrait','sequoia'),
				'as-landscape'	=> __('Sequoia landscape','sequoia'),
				'large'			=> __('Large','sequoia')
			),
			'default'		=> 'as-portrait',
			'cols'			=> 4

		),
		array(
			'name'		=> __('Slider of images or thumbnails ?','sequoia'),
			'id'		=> $prefix . 'slider_thumbs',
			'type'		=> 'radio',
			'options'	=> array(			
				'slider'		=> 'Slider',
				'thumbnails'	=> 'Thumbnails', 
			),
			'default'	=> 'slider',
			'cols'		=> 4
		),

		array(
			'name'		=> __('Thumbnails columns (if there are more then one attached image)','sequoia'),
			'desc'		=> __('type in the number of thumbnails in a row. If left empty, the default number of <strong>3</strong> will be set','sequoia'),
			'id'		=> $prefix . 'thumb_columns',
			'type'		=> 'text_small',
			'default'	=> '3',
			'cols'		=> 4
		),
		
		array(
			'name'		=> __('Slider navigation','sequoia'),
			'desc'		=> __('add previous/next arrows (appear on hover)','sequoia'),
			'id'		=> $prefix . 'slider_nav',
			'type'		=> 'checkbox',
			'cols'		=> 3
		),
		
		array(
			'name'		=> __('Slider pagination','sequoia'),
			'desc'		=> __('add previous/next arrows (appear on hover)','sequoia'),
			'id'		=> $prefix . 'slider_pagin',
			'type'		=> 'checkbox',
			'cols'		=> 3
		),
		array(
			'name'		=> __('Slider timing','sequoia'),
			'desc'		=> __('interval between slide transitions ( in milliseconds )','sequoia'),
			'id'		=> $prefix . 'slider_timer',
			'type'		=> 'text_small',
			'default'	=> '5000',
			'cols'		=> 3
		),
		array(
			'name'		=> __('Slider transition effect','sequoia'),
			'desc'		=> __('choose the slider transition effect','sequoia'),
			'id'		=> $prefix . 'slider_trans',
			'type'		=> 'select',
			'options'	=> array(
				'none'		=> 'None',
				'fade'		=> 'Fade',
				'backSlide'	=> 'Back Slide',
				'goDown'	=> 'Go Down',
				'fadeUp'	=> 'Fade Up'
			),
			'cols'		=> 3,
			'allow_none'=> false, 
			'sortable'	=> false, 
			'repeatable'=> false
		),
		array(
			'name'		=> __('Slider items in desktop','sequoia'),
			'desc'		=> __('items displayed in slider in desktop view','sequoia'),
			'id'		=> $prefix . 'items_desktop',
			'type'		=> 'text_small',
			'default'	=> '4',
			'cols'		=> 3
		),
		
		array(
			'name'		=> __('Slider items in smaller desktop','sequoia'),
			'desc'		=> __('items displayed in slider in smaller desktop view','sequoia'),
			'id'		=> $prefix . 'items_desktop_s',
			'type'		=> 'text_small',
			'default'	=> '3',
			'cols'		=> 3
		),
		array(
			'name'		=> __('Slider items in tablet','sequoia'),
			'desc'		=> __('items displayed in slider in tablet view','sequoia'),
			'id'		=> $prefix . 'items_tablet',
			'type'		=> 'text_small',
			'default'	=> '2',
			'cols'		=> 3
		),
		array(
			'name'		=> __('Slider items in mobile','sequoia'),
			'desc'		=> __('items displayed in slider in mobile view','sequoia'),
			'id'		=> $prefix . 'items_mobile',
			'type'		=> 'text_small',
			'default'	=> '1',
			'cols'		=> 3
		),
		
	);
	
	$meta_boxes[] = array(
		'title'		=> 'Gallery settings',
		'pages'		=> array('post','portfolio'),
		'fields'	=> $format_gallery_fields,
		'context'	=> 'normal',
		'priority'	=> 'high'
	);
	
	
	
	// POST FORMAT meta boxes - AUDIO:
	$format_audio_fields = array(
		array( // Text Input
			'name'		=> __('Audio file','sequoia'), 
			'id'		=> $prefix.'audio_file',
			'type'		=> 'file',
			'file_type' => 'audio',
			'desc'	=> 'upload the audio file',
		),
	);
	
	$meta_boxes[] = array(
		'title'		=> 'Audio settings',
		'pages'		=> array('post','portfolio'),
		'fields'	=> $format_audio_fields,
		'context'	=> 'normal',
		'priority'	=> 'high'
	);
	
	// POST FORMAT meta boxes - VIDEO:
	$format_video_fields = array(
		array(
			'name'		=> __('Video host site','sequoia'),
			'desc'		=> __('choose the video host service site to fetch the video from (YouTube, Vimeo etc...)','sequoia'),
			'id'		=> $prefix.'video_host',
			'type'		=> 'select',
			'options'	=> array(
				'youtube'		=> 'YouTube',
				'screenr'		=> 'Screenr',
				'vimeo'			=> 'Vimeo',
				'dailymotion'	=> 'DailyMotion',
				'yahoo'			=> 'Yahoo',
				'bliptv'		=> 'BlipTV',
				'veoh'			=> 'Veoh',
				'viddler'		=> 'Viddler',
			),
			'cols' => 6,
			'default'     => 'youtube',
		),
		array(
			'name'		=> __('Video ID','sequoia'),
			'desc'		=> __('enter the video ID number or code, NOT the whole address','sequoia'),
			'id'		=> $prefix.'video_id',
			'type'		=> 'text',
			'cols' => 6,
			'default'    => '',
		 ),
		 array(
			'name'		=> __('Width of the video','sequoia'),
			'desc'		=> __('enter the width in PERCENTAGE - DO NOT ADD UNIT, JUST NUMBER','sequoia'),
			'id'		=> $prefix.'video_width',
			'type'		=> 'text',
			'cols' => 6,
			'default'	=> '100',
		 ),
		array(
			'name'		=> __('Height of the video','sequoia'),
			'desc'		=> __('enter the height - allowed units: px, em, %','sequoia'),
			'id'		=> $prefix.'video_height',
			'type'		=> 'text',
			'cols' => 6,
			'default'	 => '350px',
		),
		array(
			'name'		=> __('Featured image or video thumbnails','sequoia'),
			'desc'		=> __('show post featured image or fetch thumbnail of video from video host service.<br /><strong>NOTE: some services do not provide high resolution image so it is advisable to use featured image. Supported: YouTube, Screenr, Vimeo, Daily Motion and BlipTV</strong>','sequoia'),
			'id'		=> $prefix.'video_thumb',
			'type'		=> 'select',
			'options'	=> array(
				'featured'		=> __('Featured image','sequoia'),
				'host_thumb'	=> __('Video host thumbnail','sequoia')
			),
			'cols' => 12,
			'default'	=> 'three',
		),
	);
	$meta_boxes[] = array(
		'title'		=> 'Video settings',
		'pages'		=> array('post','portfolio'),
		'fields'	=> $format_video_fields,
		'context'	=> 'normal',
		'priority'	=> 'high'
	);
	
	
	// POST FORMAT meta boxes - QUOTE:
	$format_quote_fields = array(
		array(
			'name'	=> __('Quote author','sequoia'), 
			'desc'	=> __('enter the name and/or title of quote author.','sequoia'),
			'id'	=> $prefix.'quote_author',
			'type'	=> 'text',
		),

		array(
			'name'	=> __('Quote author URL','sequoia'), 
			'desc'	=> __('Enter full URL including http:// .','sequoia'),
			'id'	=> $prefix.'quote_author_url',
			'type'	=> 'url',
		),

		array(
			'name'	=> __('Avatar email for avatar image','sequoia'), 
			'desc'	=> __('If quote author uses gravatar.com quote author avatar can be displayed. If set, it will override the featured image.','sequoia'),
			'id'	=> $prefix.'avatar_email',
			'type'	=> 'text',
		),
	);
	$meta_boxes[] = array(
		'title'		=> 'Quote settings',
		'pages'		=> array('post'),
		'fields'	=> $format_quote_fields,
		'context'	=> 'normal',
		'priority'	=> 'high'
	);
	
	
	// POST FORMAT meta boxes - IMAGE:
	$format_image_fields = array(
		array( 
			'name'		=> __('NOTE:','sequoia'), 
			'desc'		=> '',
			'id'		=> $prefix.'image',
			'type'		=> 'text',
			'readonly'	=> true,
			'default'	=> __('simply select featured image for this post','sequoia'),
		)
	);
	$meta_boxes[] = array(
		'title'		=> 'Image settings',
		'pages'		=> array('post','portfolio'),
		'fields'	=> $format_image_fields,
		'context'	=> 'normal',
		'priority'	=> 'high'
	);
	
	
	/**
	 *	PORTFOLIO META BOXES
	 *
	 */
	
	$portfolio_fields = array(
		array(
			'name'		=> __('Tagline or slogan','sequoia'), 
			'desc'		=> __('optional tagline text.','sequoia'),
			'id'		=> $prefix.'tagline',
			'type'		=> 'text',
		),
		array(
			'name'		=> __('Layout mode','sequoia'),
			'desc'		=> '',
			'id'		=> $prefix.'single_layout',
			'type'		=> 'select',
			'options'	=> array(
				'float_left'	=> 'Float left',
				'float_right'	=> 'Float right',
				'centered'		=> 'Centered'
			),
			'cols' => 4,
			'default'     => 'float_left',
		),
		array(
			'name'		=> __('Featured image format','sequoia'),
			'desc'		=> '',
			'id'		=> $prefix.'feat_port_image_format',
			'type'		=> 'select',
			'options'	=> array(
				'thumbnail'		=> 'Thumbnail',
				'medium'		=> 'Medium',
				'as-portrait'	=> 'Sequoia portrait',
				'as-landscape'	=> 'Sequoia landscape',
				'large'			=> 'Large'
			),
			'cols' => 4,
			'default'     => 'thumbnail',
		),
		array(
			'name'		=> __('Number of related items','sequoia'), 
			'desc'		=> '',
			'id'		=> $prefix.'related_portfolios',
			'type'		=> 'text',
			'cols'		=> 4,
			'default'	=> 3
		),
		array(
			'name'		=> __('Button URL','sequoia'), 
			'desc'		=> __('If no url or no button label (next input field), no button will be displayed.','sequoia'),
			'id'		=> $prefix.'button_url',
			'type'		=> 'text',
			'cols'		=> 6,
			'default'	=> ''
		),	
		array(
			'name'		=> __('Button label','sequoia'), 
			'desc'		=> __('If no label or no button url (previous input field), no button will be displayed.','sequoia'),
			'id'		=> $prefix.'button_label',
			'type'		=> 'text',
			'cols'		=> 6,
			'default'	=> ''
		),
		array(
			'name'		=> 'Additional content',
			'desc'		=> __('Add some more info about your project','sequoia'),
			'id'		=>  $prefix.'additional_port', 
			'type'		=> 'wysiwyg',
			'options'	=> array( 'editor_height' => '100' )

		),
	);
	$meta_boxes[] = array(
		'title'		=> 'Portfolio item settings',
		'pages'		=> array('portfolio'),
		'fields'	=> $portfolio_fields,
		'context'	=> 'normal',
		'priority'	=> 'default'
	);

	
	/**
	 *	SHOP META BOXES
	 *	- if WooCommerce plugin is active these will show
	 */
	if( $sequoia_woo_is_active ) {
	
		$single_product_fields = array( 
			array ( 
				'id'		=> $prefix.'before_catalog',
				'name'		=> 'Before product catalog',
				'desc'		=> 'Display additional content on catalog page, BEFORE products catalog.',
				'type'		=> 'wysiwyg',
				'options'	=> array( 
									'editor_height'	=> '100' 
								)
			),
			array ( 
				'id'		=> $prefix.'after_catalog',
				'name'		=> 'After product catalog',
				'desc'		=> 'Display additional content on catalog page, AFTER products catalog.',
				'type'		=> 'wysiwyg',
				'options'	=> array( 
									'editor_height'	=> '100' 
								)
			),
			
		);				
		
		$meta_boxes[] = array(
			'title'		=> 'Catalog page meta box',
			'id'		=> 'catalog-page-meta-box',
			'pages'		=> array('page'),
			'fields'	=> $single_product_fields,
			'context'	=> 'normal',
			'priority'	=> 'low'
		);
	}
	
	/**
	 *	CUSTOM HEADER IMAGE:
	 *
	 */
	$custom_header_image_fields = array(
		
		array( 
			'id'			=> $prefix.'custom_head_image', 
			'name'			=> __('Custom header background image','sequoia'),
			'desc'			=> __('Replace default featured product background image header with custom image ','sequoia'),
			'type' 			=> 'image',
			'repeatable'	=> false,
			'show_size'		=> true 
		),
		array( 
			'id'			=> $prefix.'custom_head_image_format',
			'name'			=> __('Background image format','sequoia'),
			'type'			=> 'select',
			'options'		=> array(
					'thumbnail'		=> 'Thumbnail',
					'medium'		=> 'Medium',
					'as-portrait'	=> 'Sequoia portrait',
					'as-landscape'	=> 'Sequoia landscape',
					'large'			=> 'Large',
					'full'			=> 'Full'
				),
			'cols'			=> 6,
			'allow_none'	=> false,
			'sortable'		=> false,
			'repeatable'	=> false
		),
		array( 
			'id'			=> $prefix.'custom_head_image_repeat',
			'name'			=> __('Background image repeat','sequoia'),
			'type'			=> 'select',
			'options'		=> array(
					'repeat'	=> 'Repeat',
					'no-repeat'	=> 'No repeat',
					'repeat-x'	=> 'Repeat X',
					'repeat-y'	=> 'Repeat Y'
				),
			'cols'			=> 6,
			'allow_none'	=> true,
			'sortable'		=> false,
			'repeatable'	=> false
		),
		array( 
			'id'			=> $prefix.'custom_head_image_size',
			'name'			=> __('Background image size','sequoia'),
			'type'			=> 'select',
			'options'		=> array(
					'inherit'	=> 'Inherit',
					'50%'		=> '50%',
					'100% 100%'	=> '100%',
					'cover'		=> 'Cover',
					'contain'	=> 'Contain'
				),
			'cols'			=> 6,
			'allow_none'	=> false,
			'sortable'		=> false,
			'repeatable'	=> false
		),
		
		
		
	);
	$chmf_pages = $sequoia_woo_is_active ? array('post','product','portfolio') : array('post','portfolio');
	$meta_boxes[] = array(
		'title'		=> 'Custom header image',
		'id'		=> 'custom-header-image',
		'pages'		=> $chmf_pages,
		'fields'	=> $custom_header_image_fields,
		'context'	=> 'side',
		'priority'	=> 'low'
	);
	
	
	
	// FINALLY - OUTPUT META BOXES:
	return $meta_boxes;

}
add_filter( 'as_meta_boxes', 'as_meta_boxes' );
//
//
//
/**
 *	HIDE DEFAULT META BOXES
 *
 */
// initial default hiding of meta boxes - can be overridden by "Screen options" :
add_filter('default_hidden_meta_boxes', 'hide_meta_lock', 10, 2);
function hide_meta_lock( $hidden, $screen ) {
	
	$hidden = array();
	if ( 'post' == $screen->base ) {
		$hidden = array('postexcerpt','slugdiv','postcustom','trackbacksdiv', 'commentstatusdiv', 'commentsdiv', 'authordiv', 'revisionsdiv');
	}
	return $hidden;
}
// force hiding meta boxes - indepenent on "Screen options" :
$hidden_metaboxes = apply_filters( 'sequoia_options', 'hidden_metaboxes', true);
if( $hidden_metaboxes ) {
	add_filter( 'hidden_meta_boxes', 'custom_hidden_meta_boxes' );
	function custom_hidden_meta_boxes( $hidden ) {
		$hidden[] = 'postexcerpt';
		return $hidden;
	}
}
?>