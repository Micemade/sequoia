<?php
// POST META THEME OPTIONS SETTINGS:
$of_pm				= apply_filters( 'sequoia_options', 'post_meta', array('date_author'=> 'Date and author','categories_tags'=> 'Post categories and tags','comments'=> 'Comments count') );
$date_author		= isset($of_pm['date_author']) ? true : false;
$categories_tags	= isset($of_pm['categories_tags']) ? true : false;
$comments			= isset($of_pm['comments']) ? true : false;

/**
 *	RETURN TRUE IF BLOG HAS NORE THEN ONE CATEGORY
 *
 */
function sequoia_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );
		//
		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );
		//
		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}
	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so sequoia_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so sequoia_categorized_blog should return false
		return false;
	}
}
//
/**
 *	META INFO BLOCK - generate all the post meta - date, author, tags, categories
 *
 */
if ( ! function_exists( 'as_entry_date' ) ) {
	function as_entry_date() {
		
		global $date_author;
		
		$link = esc_url( get_permalink() );
		$time	= esc_attr( get_the_time() );
		$day	= esc_attr( get_the_date('d') );
		$month	= esc_attr( get_the_date('M') );
		$year	= esc_attr( get_the_date('Y') );
		$full_date = esc_attr( get_the_date( 'c' ) );
		
		// date/time:
		
		//$output = '<div class="meta">';
		
		$output = '<a href="'.$link.'" title="'.$time.'" rel="bookmark" class="date-time"><time class="entry-date" datetime="'.$full_date.'" ><span class="day">'.$day.'</span> <span class="month">'.$month.'</span></time></a>';
		
		//$output .= as_post_format_icon();
		
		//$output .= '</div>';
		
		echo $date_author ? $output : '';
	}
}
if ( ! function_exists( 'as_entry_author' ) ) {
	function as_entry_author() {
	
		global $date_author;
		
		$author_link = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
		$author = esc_html( get_the_author() );
		
		// author:
		$by  	=  __('by ','sequoia');
		$output = '<a class="url fn n author" href="'.$author_link.'" title="'. esc_attr(__('View all post by ','sequoia') .$author ).'" rel="author">'.$by.$author.'</a>';
		
		echo $date_author ? $output : '';
	}
}


if ( ! function_exists( 'as_entryMeta_dateUser' ) ) :
function as_entryMeta_dateUser() {
		
	$link = esc_url( get_permalink() );
	$time	= esc_attr( get_the_time() );
	
	$full_date = esc_attr( get_the_date( 'c' ) );
	$date_formatted = esc_html( get_the_date( apply_filters( 'sequoia_options', 'post_date_format', 'd M Y' ) ) );
	
	$author_link = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
	$author = esc_html( get_the_author() );
		
	// date/time
	$output  = '<span class="date_meta"><span class="icon-calendar meta-icon"></span>';
	$output .= '<span class="hover-box"><a href="'.$link.'" title="'.$time.'" rel="bookmark" class="date-time button-mini"><time class="entry-date" datetime="'.$full_date.'" >'.$date_formatted.'</time></a><span class="arrow-down"></span></span>';
	$output .= '</span>';
	
	// author:
	$output .='<span class="user_meta"><span class="icon-user meta-icon"></span>';
	$output .= '<span class="hover-box"><a class="url fn n author vcard" href="'.$author_link.'" title="'. esc_attr(__('View all post by ','sequoia') .$author ).'" rel="author">'.$author.'</a><span class="arrow-down"></span></span>';
	$output .= '</span>';
			
	echo $output;
	
}
endif;
if( ! function_exists( 'as_entryMeta_cats_tags' )) :

	function as_entryMeta_cats_tags( $style = 'default' ) {
		
		global $post,$categories_tags;
		
		//GET ALL THE TERMS WITH LINKS:
		
		$cats = get_the_terms( $post->ID, 'category' );		
		if ( $cats && ! is_wp_error( $cats ) ) { 
			$cats_list = '';
			foreach ( $cats as $cat ) {
				$cats_list .= '<a href="'.get_term_link($cat->slug, 'category') .'">'.$cat->name .'</a>';
			}					
		}else{
			$cats_list = '';
		};
		$tags = get_the_terms( $post->ID, 'post_tag' );		
		if ( $tags && ! is_wp_error( $tags ) ) { 
			$tags_list = '';
			foreach ( $tags as $tag ) {
				$tags_list .= '<a href="'.get_term_link($tag->slug, 'post_tag') .'">'.$tag->name .'</a>';
			}					
		}else{
			$tags_list = '';
		};
		$port_cats = get_the_terms( $post->ID, 'portfolio_category' );		
		if ( $port_cats && ! is_wp_error( $port_cats ) ) { 
			$port_cats_list = '';
			foreach ( $port_cats as $cat ) {
				$port_cats_list .= '<a href="'.get_term_link($cat->slug, 'portfolio_category') .'">'.$cat->name .'</a>';
			}					
		}else{
			$port_cats_list = '';
		};
		$port_tags = get_the_terms( $post->ID, 'portfolio_tag' );		
		if ( $port_tags && ! is_wp_error( $port_tags ) ) { 
			$port_tags_list = '';
			foreach ( $port_tags as $tag ) {
				$port_tags_list .= '<a href="'.get_term_link($tag->slug, 'portfolio_tag') .'">'.$tag->name .'</a>';
			}					
		}else{
			$port_tags_list = '';
		};
		
		
		$output = '';
		// DISPLAY ALL THE TERMS WITH LINKS :
		if ( $cats_list ) :
		
			$output .= '<span class="cat_meta">';
			$output .= '<a class="icon-folder meta-icon"></a><span class="hover-box">'.$cats_list.'<span class="arrow-down"></span></span>';
			$output .= '</span>';
			
		elseif ( $port_cats_list  && !is_wp_error($port_cats_list) ) :
		
			$output .= '<span class="cat_meta">';
			$output .= '<a class="icon-folder meta-icon"></a><span class="hover-box">'.$port_cats_list.'<span class="arrow-down"></span></span>';
			$output .= '</span>';	
			
		endif;
		//
		//
		if ( $tags_list ) :
		
			$output .=  '<span class="tag_meta">';
			$output .=  '<a class="icon-tags meta-icon"></a><span class="hover-box">'.$tags_list.'<span class="arrow-down"></span></span>';
			echo '</span>';
		
		
		elseif ( $port_tags_list && !is_wp_error($port_tags_list) ):
		
			$output .=  '<span class="tag_meta ">';
			$output .= '<a class="icon-tags meta-icon"></a><span class="hover-box">'.$port_tags_list.'<span class="arrow-down"></span></span>';
			$output .= '</span>';	
			
		endif;
		
		echo $categories_tags ? $output : '';
	}
endif;

if( ! function_exists( 'as_entryMeta_comments' )) :
	function as_entryMeta_comments( $style = 'default' ) {
		
		global $comments;
		
		if( !$comments ) return;
		
		if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) :
			
			$no_comments = ' 0';
			$one_comment = ' 1';
			$more_comments = ' %';
			$css_class = '';
			
			echo '<span class="comments_meta"><a class="icon-bubble meta-icon"></a><span class="hover-box">';

			comments_popup_link( $no_comments, $one_comment, $more_comments ,$css_class );
		
			echo '<span class="arrow-down"></span></span></span>';
			
		endif;
	}
endif;


if( ! function_exists( 'as_entryMeta_permalink' )) :
	function as_entryMeta_permalink() {
	global $post;
	?>
	<span class="permalink">
		
			<span class="icon-link meta-icon"></span>
			
			<span class="hover-box">
				
				<a href="<?php esc_attr(the_permalink());?>" title="<?php esc_attr(the_title());?>">
				<?php echo __('More ...','sequoia'); ?>
				</a>
				
				<span class="arrow-down"></span>
				
			</span>
			
		</span>	
	<?php }
endif;
?>