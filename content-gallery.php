<?php
/**
 *	The template part used for displaying page content - GALLERY template
 *
 *	@since sequoia 1.0
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$archive_enteranim	= apply_filters( 'sequoia_options', 'post_enter_anim_archive', 'fadeIn' );
$tax_enteranim		= apply_filters( 'sequoia_options', 'post_enter_anim_tax', 'fadeIn' );
$enter_anim			= $archive_enteranim ? $archive_enteranim : $tax_enteranim;
//
// POST META:
$of_pm = apply_filters( 'sequoia_options', 'post_meta', array('date_author'	=> 'Date and author','categories_tags'	=> 'Post categories and tags','comments' => 'Comments count' ) );
$date_author = isset($of_pm['date_author']) ? true : false;
$categories_tags = isset($of_pm['categories_tags']) ? true : false;
$comments = isset($of_pm['comments']) ? true : false;
//
// CUSTOM META:
$hide_title		= get_post_meta( get_the_ID(),'as_hide_archive_titles', true);
$hide_feat_img	= get_post_meta( get_the_ID(), 'as_hide_featured_image', true);
//
$has_content = get_the_content();
//
$classes = array();
$classes[] = ($enter_anim != 'none') ? ' to-anim' : '';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($classes); ?>>
		
	<?php if( !$hide_title ) {?>
	<a href="<?php esc_attr(the_permalink());?>" title="<?php the_title_attribute();?>" class="post-link">
	
		<h2 class="post-title"><?php the_title(); ?></h2>
		
	</a>	
	<?php } ?>	
	
	<div class="meta">
		<?php as_entry_author(); as_entry_date();?>
	</div>
	
	<?php 
	// WP GALLERY shortcode img id's
	$wpgall_ids = apply_filters('as_wpgallery_ids','as_wp_gallery');
	//
	// AS GALLERY POST META:
	//
	$gall_img_array		= get_post_meta( get_the_ID(),'as_gallery_images' );
	$gall_image_format	= get_post_meta( get_the_ID(),'as_gall_image_format', true ) ; 
	$slider_thumbs		= get_post_meta( get_the_ID(),'as_slider_thumbs', true ); 
	$thumb_columns		= get_post_meta( get_the_ID(),'as_thumb_columns', true ) ; 
	// image ID's from meta:
	$images_ids = '';
	
	
	if( !empty( $gall_img_array ) ) {
		foreach ( $gall_img_array as $gall_img_id ){
			$images_ids .= $gall_img_id .','; 
		}
	}
	if( !empty( $wpgall_ids ) ) {
		$images_ids = implode(', ', $wpgall_ids); // get images from WP gallery
	}else{
		$images_ids = implode(', ', $gall_img_array); // get images from AS gallery
	}
	
	// function to display images with link to larger:
	echo as_gallery_output( get_the_ID(), $images_ids, $slider_thumbs, $thumb_columns, $gall_image_format );
	?>
	
	
	<div class="post-content<?php echo $hide_feat_img ? ' no-feat-img' : ''; ?>">
	
		<?php
		//echo apply_filters('as_custom_excerpt', 150, true) ; 
		
		the_excerpt();
		
		$wlp_args = array( 
				'before'		=> '<div class="page-link"><p>' . __( 'Pages:', 'sequoia' ) . '</p>',
				'after'			=> '</div>',
				'link_before'	=> '<span>',
				'link_after'	=> '</span>',
			);
		
		wp_link_pages( $wlp_args );
		?>
	
	
	</div>
				
	<div class="clearfix"></div>
	
	<div class="post-meta-bottom">
	
		<?php
		as_entryMeta_comments();
		
		if( ( has_category() || has_tag() || has_term( '', 'portfolio_category' ) || has_term( '', 'portfolio_tag' )) ) {
		as_entryMeta_cats_tags();
		}
		?>
		
	</div>
	

</article><!-- #post-<?php the_ID(); ?> -->