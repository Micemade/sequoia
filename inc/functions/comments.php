<?php
if ( ! function_exists( 'comment_function' ) ) :
function comment_function( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'sequoia' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'sequoia' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			
			<?php echo get_avatar( $comment, 80 );?>
			
			<section class="comment-content comment">
			
				<div class="arrow-left"></div>
				
				<header class="comment-meta comment-author vcard">
										
					<?php
						
						printf( '<cite class="fn"> %2$s %1$s, </cite>',
							get_comment_author_link(),
							// If current post author is also comment author, make it known visually.
							( $comment->user_id === $post->post_author ) ? '<span class="author"> ' . __( 'Comment author', 'sequoia' ) . '</span>' : ''
						);
						printf( '<a href="%1$s"><span class="icon-calendar comment-icons"></span><time datetime="%2$s">%3$s</time></a>',
							esc_url( get_comment_link( $comment->comment_ID ) ),
							get_comment_time( 'c' ),
							/* translators: 1: date, 2: time */
							sprintf( __( '%1$s <span class="icon-clock-2 comment-icons"></span> %2$s', 'sequoia' ), get_comment_date(), get_comment_time() )
						);
					?>
				</header><!-- .comment-meta -->

				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'sequoia' ); ?></p>
				<?php endif; ?>

			
				<div class="comment-text-wrap"><?php comment_text(); ?></div>
				
				<?php edit_comment_link( __( 'Edit', 'sequoia' ), '<p class="edit-link">', '</p>' ); ?>
				
				<div class="clearfix"></div>
				
			</section><!-- .comment-content -->

			<div class="reply">
			
				<?php
				$theme_args = array(
					'reply_text'	=> __( 'Reply', 'sequoia' ),
					'before'		=> '',
					'after'			=> ' <span class="icon-arrow-down-6"></span>',
					'depth'			=> $depth,
					'max_depth'		=> $args['max_depth']
				);
				comment_reply_link( array_merge( $args, $theme_args ) ); ?>
				
			</div><!-- .reply -->
			
		</article><!-- #comment-## -->
		
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

/*
 * Change the comment reply link to use 'Reply to &lt;Author First Name>'
 */
function as_comment_author_to_reply_link($link, $args, $comment){
 
    $comment = get_comment( $comment );
 
    // If no comment author is blank, use 'Anonymous'
    if ( empty($comment->comment_author) ) {
        if (!empty($comment->user_id)){
            $user=get_userdata($comment->user_id);
            $author=$user->user_login;
        } else {
            $author = __('Anonymous','sequoia');
        }
    } else {
        $author = $comment->comment_author;
    }
 
    // If the user provided more than a first name, use only first name
    if(strpos($author, ' ')){
        $author = substr($author, 0, strpos($author, ' '));
    }
 
    // Replace Reply Link with "Reply to &lt;Author First Name>"
    $reply_link_text = $args['reply_text'];
	$link = str_replace($reply_link_text, __( 'Reply to', 'sequoia' ).' '. $author, $link);
 
    return $link;
}
add_filter('comment_reply_link', 'as_comment_author_to_reply_link', 10, 3);
?>