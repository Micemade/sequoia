<?php
/**
 * The Template for displaying all single posts.
 *
 * @since sequoia 1.0
 */
get_header();
$layout = apply_filters( 'sequoia_options', 'layout', 'float_left' );
//
// POST CUSTOM META
$hide_title			= get_post_meta( get_the_ID() ,'as_hide_title', true);
$hide_feat_img		= get_post_meta( get_the_ID() ,'as_hide_featured_image', true);
//if video post format:
$featured_or_thumb	= get_post_meta( get_the_ID(),'as_video_thumb', true);
?>


<header class="page-header">

	<?php	
	$single_blog_title_bcktoggle = apply_filters( 'sequoia_options', 'single_blog_title_bcktoggle', true ); // if bckimg is set to true
	$blog_title_backimg = apply_filters( 'sequoia_options', 'blog_title_backimg', get_template_directory_uri(). '/img/header-archive.jpg' ); // general blog bckimg 
	// custom header image:
	$custom_head_image	= get_post_meta( get_the_ID(), 'as_custom_head_image', true); // custom head image
	$custom_head_format	= get_post_meta( get_the_ID(), 'as_custom_head_image_format', true);
	$custom_head_repeat	= get_post_meta( get_the_ID(), 'as_custom_head_image_repeat', true);
	$custom_head_size	= get_post_meta( get_the_ID(), 'as_custom_head_image_size', true);
	
	if( $single_blog_title_bcktoggle && !$hide_feat_img ) {
		
		if( get_post_format() == 'gallery' ) {
			// WP gallery ID's
			$wpgall_id = apply_filters('as_wpgallery_ids','as_wp_gallery');
			//
			// image ID's from custom meta (AS GALLERY):
			$gall_img_array = get_post_meta( get_the_ID(),'as_gallery_images');
			$images_ids = array();
			if( isset( $gall_img_array ) ) {
				foreach ( $gall_img_array as $gall_img_id ){
					$images_ids[] = $gall_img_id; 
				}
			}
			if( !empty($wpgall_id) ) {
				$attach_ID = $wpgall_id[0]; // get first image from WP gallery
			}elseif( !empty($images_ids) ){
				$attach_ID = $images_ids[0]; // get first image from AS gallery
			}else{
				$attach_ID = '';
			}
			
		}elseif( $custom_head_image ){
		
			if( $custom_head_repeat || $custom_head_size ) {
				echo '<style>.header-background {';
				echo $custom_head_repeat	? 'background-repeat:'.$custom_head_repeat.';' : '';
				echo $custom_head_size		? 'background-size:'.$custom_head_size.' !important;' : '';
				echo '}</style>';
			}
			$attach_ID = $custom_head_image;
		
		}elseif( has_post_thumbnail()){
			
			$attach_ID = get_post_thumbnail_id();
		
		}else{
			$attach_ID = '';
		}
		
		
		
		if( $attach_ID ) { // if galleries or post thumbnails
		
			$image = wp_get_attachment_image_src( $attach_ID, 'as-landscape' );
			$image = $image[0];
			
		}elseif( get_post_format() == 'video' && $featured_or_thumb == 'host_thumb') { // if video host thumb
			
			$image =  as_video_thumbs();
		
		}else{ // else do the theme options image
			$image =  $blog_title_backimg;
		}// or, no image
			
		echo'<div class="header-background'. ( UNDERHEAD_IMAGE ? ' under-head' : '') .'" style="background-image: url('.$image.');"></div>';
	}
	?>

	<div class="row" style="position: relative; z-index: 6;">
	
		<div class="small-12">
		
			<h1 class="page-title"><?php the_title(); ?></h1>
	
			<?php
			global $wp_query, $post;
			$thisID			= $wp_query->post->ID;
			
			$author_id		= $post->post_author;
			$author_posts	= esc_url(get_author_posts_url( $author_id ) );
			$author_name	= esc_html(get_the_author_meta( 'display_name', $author_id ) );
			$by				= __('by ','sequoia');
			echo '<a href="'.$author_posts.'" title="'. esc_attr(__('View all post by ','sequoia') .$author_name ).'" rel="author" class="author">'.$by.$author_name. '</a>';
			?>

		
		</div>
	
	</div>
	
</header>

	
<div class="row">
		
	<?php
	// PORTFOLIO SINGLE TAGLINE
	$tagline		= get_post_meta( $thisID, 'as_tagline', true) ; 
	echo $tagline ? '<div class="tagline">'.$tagline.'</div>' : ''; 
	?>
	
	<div id="primary" class="large-<?php echo ($post_type == 'portfolio'|| $layout =='full_width' ) ? '12' : '9'; ?> <?php echo $layout ? $layout : null; ?> medium-12 small-12 column" role="main">

		
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>

			<?php comments_template( '', true ); ?>

		<?php endwhile; ?>


	</div><!-- #primary -->

	<?php 
	if( $post_type != 'portfolio' && $layout != 'full_width' ) {
		
		get_sidebar();
		
	}
	?>
		
</div><!-- /.row -->
	
<?php get_footer(); ?>