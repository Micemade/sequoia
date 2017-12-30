<?php
/********************************************
Display pagination to other pages when applicable
********************************************/
if ( ! function_exists( 'as_pagination' ) ):
//
function as_pagination( $nav_id ) {
	global $wp_query, $wp_rewrite;
	
	$output = '<div class="pagination" id="'.$nav_id.'">';
	
	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;

	$pagination = array(
		'base'			=> @add_query_arg('page','%#%'),
		//'format'		=> '?page=%#%',
		'format'		=> 'page',
		'total'			=> $wp_query->max_num_pages,
		'current'		=> $current,
		'show_all'		=> false,
		'end_size'		=> 4,
		'mid_size'		=> 4,
		'type'			=> 'list',
		'prev_text'		=> __('&laquo;','sequoia'),
		'next_text'		=> __('&raquo;','sequoia')
		);
	if( $wp_rewrite->using_permalinks() )
		$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );
	if( !empty($wp_query->query_vars['s']) )
		$pagination['add_args'] = array( 's' => get_query_var( 's' ) );
	$output .= paginate_links( $pagination );
	$output .= '</div>';
	
	echo $output;
		
}
endif;// as_pagination
/**
 *	FOR CONDITIONAL PAGINATION USAGE:
 *
 */
if ( ! function_exists( 'as_show_pagination' ) ):
function as_show_pagination() {
	global $wp_query;
	return ($wp_query -> max_num_pages > 1);
	
}
endif;// as_show_pagination
?>