<?php
/**
 *	NATIVE EXCERPT LENGTH 
 */
function as_custom_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'as_custom_excerpt_length', 999 );
/**
 *	EXCERPT "Read more" 
 */
function new_excerpt_more( $more ) {
	return ' <a  href="'. get_permalink( get_the_ID() ) . '"> ... ' . __('Read More', 'sequoia') . '</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );
/**
 *	AS CUSTOM EXCERPT LENGTH
 *
 *	@param int $chars - length of excerpt in characters
 */
function as_custom_excerpt_func( $chars = 150, $has_readmore = true) {
	
	global $post;
	$ellipsis	= false;
	$text		= get_the_content();
	$readmore	= '<br /><a href="'.get_permalink().'" class="tiny button">'. __('Read more','sequoia') .'</a>';
	$text		= $text . " ";
	$text		= strip_tags($text);
	$text		= strip_shortcodes($text);  //strip_shortcodes - WP core function (WP Codex)
	
	if( $chars != 'full' ) {
		if( strlen($text) > $chars )
			$ellipsis = true;
		$text = substr($text,0,$chars);
		$text = substr($text,0,strrpos($text,' '));
		if( $ellipsis == true )
			$text = $text . '... ' . ($has_readmore ? $readmore : '') ;
	}
	return $text;
}
add_filter( 'as_custom_excerpt','as_custom_excerpt_func', 10, 2 );
//
/**
 *	MENU EXCERPT
 *
 *	@param int $chars - length of excerpt in characters
 *	@param string $text
 */
function as_menu_excerpt_func( $text, $chars = 150 ) {
	
	$ellipsis = false;
	$text = $text . " ";
	$text = strip_tags($text);
	$text = strip_shortcodes($text);  //strip_shortcodes - WP core function (WP Codex)
	
	if($chars != 'full') {
		if( strlen($text) > $chars )
			$ellipsis = true;
		$text = substr($text,0,$chars);
		$text = substr($text,0,strrpos($text,' '));
		if( $ellipsis == true )
			$text = $text . "...";
	}
	return $text;
}
add_filter('as_menu_excerpt','as_menu_excerpt_func', 10, 2);
//
//
/**
 *	POST FORMAT ICON function.
 *
 *	create icons using icon font using class html attribute.
 *
 */
if( !function_exists('as_post_format_icon')) {
	function as_post_format_icon() {
		global $post;
		$id = get_the_ID();
		$post_type = get_post_type($id);
		$post_format = get_post_format($id);
		
		if( $post_type == 'product' ) {
		
			$icon_class = 'icon-cart-2';
		
		}elseif( $post_format == 'video' ) {
			$icon_class = 'icon-play';
		}elseif( $post_format == 'audio' ) {
			$icon_class = 'icon-bullhorn';
		}elseif( $post_format == 'gallery' ) {
			$icon_class = ' icon-images';
		}elseif( $post_format == 'image' ) {
			$icon_class = 'icon-image';
		}elseif( $post_format == 'quote'  ) {
			$icon_class = 'icon-quotes-left';
		}elseif( $post_format == '' ) {
			$icon_class = 'icon-blog';
		}else{
			$icon_class = 'icon-blog';
		}

		if( $icon_class ) {
			$post_format_icon_output = '<div class="icon"><span class="'.$icon_class.'" aria-hidden="true" ></span></div>';
			return $post_format_icon_output;
		}
	}
}
/**
 *	POST FORMAT ACTION ICON.
 *
 *	create icons using icon font using class attribute.
 *	used in items hover for prettyPhoto modal window opening big image, gallery, video, audio or quote
 *
 */
if( !function_exists('as_post_format_icon_action')) {
	function as_post_format_icon_action() {
		global $post;
		$id = get_the_ID();
		$post_type = get_post_type($id);
		$post_format = get_post_format($id);
		
		if( $post_type == 'product' ) {
		
			$icon_class = 'icon-zoom-in';
		
		}elseif( $post_format == 'video' || $post_format == 'audio') {
			$icon_class = 'icon-play';
		}elseif( $post_format == 'gallery' || $post_format == 'image' || $post_format == 'quote' || $post_format == '') {
		
			$icon_class = 'icon-zoom-in';
		}

		$post_format_icon_action = '<div class="icon '.$icon_class.'" aria-hidden="true"></div>';
		
		return $post_format_icon_action;
	}
}
/**
 *	PREVIUOS / NEXT POST LINKS.
 *
 *	replaces default post navigation function to create custom html output.
 *
 */
function as_prev_next_post() {
	
	$output = '<nav class="nav-single">';
		
		$prev_icon		= '<span class="icon icon-arrow-left-5" aria-hidden="true"></span>';
		$next_icon		= '<span class="icon icon-arrow-right-5" aria-hidden="true"></span>';
		$no_prev_next	= '<span class="icon icon-close" aria-hidden="true"></span>';
		
		$prevPost	= get_previous_post();
		$prevURL	= $prevPost ? get_permalink($prevPost->ID) : '';
		$prevTitle	= $prevPost ? $prevPost->post_title : '';
		$prevPrefix = __('Previous entry: ','sequoia');
		$nextPost	= get_next_post();
		$nextURL	= $nextPost ? get_permalink($nextPost->ID) : '';
		$nextTitle	= $nextPost ? $nextPost->post_title : '';
		$nextPrefix = __('Next entry: ','sequoia');
		
		if( $prevPost ) {
		$output .= '<span class="nav-previous">';
			$output .= '<a href="'. $prevURL .'" rel="prev" title="'.$prevPrefix . esc_attr($prevTitle) .'" class="left tip-top" data-tooltip data-options="disable_for_touch:true">';
			$output .= $prev_icon;
			$output .= '</a></span>';
		}else{
			$output .= '<span class="nav-previous"><span class="no-prev-next">'.$no_prev_next.'</span></span>';
		};
		
		if( $nextPost ) {
		$output .= '<span class="nav-next">';
			$output .= '<a href="'. $nextURL .'" rel="next" title="'.$nextPrefix . esc_attr($nextTitle). '" class="right tip-top" data-tooltip data-options="disable_for_touch:true">';
			$output .= $next_icon;
			$output .= '</a></span>';
		}else{
			$output .= '<span class="nav-next"><span class="no-prev-next">'.$no_prev_next.'</span></span>';
		};
		
	$output .= '</nav><!-- .nav-single -->';
	
	return $output;
}
//
//
/* Filter the content of chat posts. */
add_filter( 'the_content', 'as_format_chat_content' );
/* Auto-add paragraphs to the chat text. */
add_filter( 'as_post_format_chat_text', 'wpautop' );

/**
 * This function filters the post content when viewing a post with the "chat" post format.  It formats the 
 * content with structured HTML markup to make it easy for theme developers to style chat posts.  The 
 * advantage of this solution is that it allows for more than two speakers (like most solutions).  You can 
 * have 100s of speakers in your chat post, each with their own, unique classes for styling.
 *
 * @author David Chandra
 * @link http://www.turtlepod.org
 * @author Justin Tadlock
 * @link http://justintadlock.com
 * @copyright Copyright (c) 2012
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link http://justintadlock.com/archives/2012/08/21/post-formats-chat
 *
 * @global array $_post_format_chat_ids An array of IDs for the chat rows based on the author.
 * @param string $content The content of the post.
 * @return string $chat_output The formatted content of the post.
 */
function as_format_chat_content( $content ) {
	global $_post_format_chat_ids;

	/* If this is not a 'chat' post, return the content. */
	if ( !has_post_format( 'chat' ) )
		return $content;

	/* Set the global variable of speaker IDs to a new, empty array for this chat. */
	$_post_format_chat_ids = array();

	/* Allow the separator (separator for speaker/text) to be filtered. */
	$separator = apply_filters( 'my_post_format_chat_separator', ':' );

	/* Open the chat transcript div and give it a unique ID based on the post ID. */
	$chat_output = "\n\t\t\t" . '<div id="chat-transcript-' . esc_attr( get_the_ID() ) . '" class="chat-transcript">';

	/* Split the content to get individual chat rows. */
	$chat_rows = preg_split( "/(\r?\n)+|(<br\s*\/?>\s*)+/", $content );

	/* Loop through each row and format the output. */
	foreach ( $chat_rows as $chat_row ) {

		/* If a speaker is found, create a new chat row with speaker and text. */
		if ( strpos( $chat_row, $separator ) ) {

			/* Split the chat row into author/text. */
			$chat_row_split = explode( $separator, trim( $chat_row ), 2 );

			/* Get the chat author and strip tags. */
			$chat_author = strip_tags( trim( $chat_row_split[0] ) );

			/* Get the chat text. */
			$chat_text = trim( $chat_row_split[1] );

			/* Get the chat row ID (based on chat author) to give a specific class to each row for styling. */
			$speaker_id = as_format_chat_row_id( $chat_author );

			/* Open the chat row. */
			$chat_output .= "\n\t\t\t\t" . '<div class="chat-row ' . sanitize_html_class( "chat-speaker-{$speaker_id}" ) . '">';

			/* Add the chat row author. */
			$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-author ' . sanitize_html_class( strtolower( "chat-author-{$chat_author}" ) ) . ' vcard"><cite class="fn">' . apply_filters( 'my_post_format_chat_author', $chat_author, $speaker_id ) . '</cite>' . $separator . '</div>';

			/* Add the chat row text. */
			$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-text">' . str_replace( array( "\r", "\n", "\t" ), '', apply_filters( 'as_post_format_chat_text', $chat_text, $chat_author, $speaker_id ) ) . '</div>';

			/* Close the chat row. */
			$chat_output .= "\n\t\t\t\t" . '</div><!-- .chat-row -->';
		}

		/**
		 * If no author is found, assume this is a separate paragraph of text that belongs to the
		 * previous speaker and label it as such, but let's still create a new row.
		 */
		else {

			/* Make sure we have text. */
			if ( !empty( $chat_row ) ) {

				/* Open the chat row. */
				$chat_output .= "\n\t\t\t\t" . '<div class="chat-row ' . sanitize_html_class( "chat-speaker-{$speaker_id}" ) . '">';

				/* Don't add a chat row author.  The label for the previous row should suffice. */

				/* Add the chat row text. */
				$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-text">' . str_replace( array( "\r", "\n", "\t" ), '', apply_filters( 'as_post_format_chat_text', $chat_row, $chat_author, $speaker_id ) ) . '</div>';

				/* Close the chat row. */
				$chat_output .= "\n\t\t\t</div><!-- .chat-row -->";
			}
		}
	}

	/* Close the chat transcript div. */
	$chat_output .= "\n\t\t\t</div><!-- .chat-transcript -->\n";

	/* Return the chat content and apply filters for developers. */
	return apply_filters( 'my_post_format_chat_content', $chat_output );
}

/**
 * This function returns an ID based on the provided chat author name.  It keeps these IDs in a global 
 * array and makes sure we have a unique set of IDs.  The purpose of this function is to provide an "ID"
 * that will be used in an HTML class for individual chat rows so they can be styled.  So, speaker "John" 
 * will always have the same class each time he speaks.  And, speaker "Mary" will have a different class 
 * from "John" but will have the same class each time she speaks.
 *
 * @author David Chandra
 * @link http://www.turtlepod.org
 * @author Justin Tadlock
 * @link http://justintadlock.com
 * @copyright Copyright (c) 2012
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link http://justintadlock.com/archives/2012/08/21/post-formats-chat
 *
 * @global array $_post_format_chat_ids An array of IDs for the chat rows based on the author.
 * @param string $chat_author Author of the current chat row.
 * @return int The ID for the chat row based on the author.
 */
function as_format_chat_row_id( $chat_author ) {
	global $_post_format_chat_ids;

	/* Let's sanitize the chat author to avoid craziness and differences like "John" and "john". */
	$chat_author = strtolower( strip_tags( $chat_author ) );

	/* Add the chat author to the array. */
	$_post_format_chat_ids[] = $chat_author;

	/* Make sure the array only holds unique values. */
	$_post_format_chat_ids = array_unique( $_post_format_chat_ids );

	/* Return the array key for the chat author and add "1" to avoid an ID of "0". */
	return absint( array_search( $chat_author, $_post_format_chat_ids ) ) + 1;
}
/** end chat format */


/**
 *	META BOX AND FIELD FOR FEATURED ITEM.
 *
 * Adds a box to the main column on the Post and Page edit screens.
 */
function as_featured_custom_box() {

    $screens =  array( 'portfolio', 'slide' );
	
	foreach ( $screens as $screen ) {

        add_meta_box(
            '_featured_item_metabox',		// $id
            __( 'Featured', 'sequoia' ),	// $title
            'as_inner_custom_box',			// $callback
			$screen ,						// $post_type
			'side',							// $context
			'high'							// $priority
											// $callback_args
        );
    }
}
add_action( 'add_meta_boxes', 'as_featured_custom_box' );
/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function as_inner_custom_box( $post ) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'as_inner_custom_box', 'as_inner_custom_box_nonce' );

	/*
	* Use get_post_meta() to retrieve an existing value
	* from the database and use the value for the form.
	*/
	$value = get_post_meta( $post->ID, 'as_featured_item', true );

	echo '<label for="as_featured_field">'. _e( "Make this item featured", 'sequoia' ) .'</label>';
	echo '<input type="checkbox" id="as_featured_field" name="as_featured_field" value="1" '. checked( 1, $value, false ) .' size="25" style="float: right;"/>';

}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function as_featured_save_postdata( $post_id ) {

	/*
	* We need to verify this came from the our screen and with proper authorization,
	* because save_post can be triggered at other times.
	*/

	// Check if our nonce is set.
	if ( ! isset( $_POST['as_inner_custom_box_nonce'] ) )
	return $post_id;

		$nonce = $_POST['as_inner_custom_box_nonce'];

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $nonce, 'as_inner_custom_box' ) )
		return $post_id;

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return $post_id;

	// Check the user's permissions.
	if ( 'page' == $_POST['post_type'] ) {

	if ( ! current_user_can( 'edit_page', $post_id ) )
		return $post_id;

	} else {

	if ( ! current_user_can( 'edit_post', $post_id ) )
		return $post_id;
	}

	/* OK, its safe for us to save the data now. */

	// Sanitize user input.
	$mydata = sanitize_text_field( $_POST['as_featured_field'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, 'as_featured_item', $mydata );
}
add_action( 'save_post', 'as_featured_save_postdata' );
/** end FEATURED META BOX */
?>