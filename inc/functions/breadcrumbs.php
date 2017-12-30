<?php
function dimox_breadcrumbs() {  
  
    global $delimiter;
	
	/* === OPTIONS === */  
    $text['home']     = __('Home','sequoia'); // text for the 'Home' link  
    $text['category'] = __('Archive by Category "%s"','sequoia'); // text for a category page  
    $text['search']   = __('Search Results for "%s" Query','sequoia'); // text for a search results page  
    $text['tag']      = __('Posts Tagged "%s"','sequoia'); // text for a tag page  
    $text['author']   = __('Articles Posted by %s','sequoia'); // text for an author page  
    $text['404']      = __('Error 404','sequoia'); // text for the 404 page  
  
    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show  
    $showOnHome  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show  
    // $delimiter   = '<span class="delimiter"> &#xe174; </span>'; // delimiter between crumbs - set globally in functions.php
    $before      = '<span class="active">'; // tag before the current crumb  
    $after       = '</span>'; // tag after the current crumb  
    /* === END OF OPTIONS === */  
  
    global $post;  
    $homeLink = home_url() . '/';  
    $linkBefore = '<span typeof="v:Breadcrumb">';
    $linkAfter = '</span>';  
    $linkAttr = ' rel="v:url" property="v:title"';  
    $link = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;  
  
    if (is_home() || is_front_page()) {  
  
        if ($showOnHome == 1) echo '<div class="breadcrumbs"><a href="' . $homeLink . '">' . $text['home'] . '</a></div>';  
  
    } else {  
  
        echo '<nav class="breadcrumbs">' . sprintf($link, $homeLink, $text['home']) . $delimiter;  
  
        if ( is_category() ) {  
            $thisCat = get_category(get_query_var('cat'), false);  
            if ($thisCat->parent != 0) {  
                $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);  
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);  
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);  
                echo $cats;  
            }  
            echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;  
  
        } elseif ( is_search() ) {  
            echo $before . sprintf($text['search'], get_search_query()) . $after;  
  
        } elseif ( is_day() ) {  
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;  
            echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;  
            echo $before . get_the_time('d') . $after;  
  
        } elseif ( is_month() ) {  
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;  
            echo $before . get_the_time('F') . $after;  
  
        } elseif ( is_year() ) {  
            echo $before . get_the_time('Y') . $after;  
  
		}elseif ( get_post_type() != 'post' && get_post_type() != 'page' && !is_404() ) {

			$post_type = get_post_type_object( get_post_type() );

			$slug = $post_type->rewrite;
								
				echo $before . '<a href="' . get_post_type_archive_link( get_post_type() ) . '">' . $post_type->labels->name . '</a>' . $after . $delimiter;
				
				$taxonomy_objects = get_object_taxonomies( 'post', 'objects' );
				
				echo get_the_term_list($post->ID, 'portfolio_category', '', ', ', ' ' );
			
			echo $before . $delimiter . get_the_title() . $after;

		}  
		/*
		elseif ( get_post_type() == 'portfolio'  ) {
		
			$post_type = get_post_type_object( get_post_type() );
			$slug = $post_type->rewrite;
			echo $before . '<a href="' . get_post_type_archive_link( get_post_type() ) . '">' . $post_type->labels->singular_name . '</a>' . $after . $delimiter;
			
			echo get_the_term_list($post->ID, 'portfolio_category', '', ', ', ' ' );
			if( is_singular() ) echo $before .  $delimiter .' '. get_the_title() . $after;
	   
		} 
		*/
		elseif ( is_single() && !is_attachment() ) {  
            if ( get_post_type() != 'post' ) {  
                $post_type = get_post_type_object(get_post_type());  
                $slug = $post_type->rewrite;  
                printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);  
                if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;  
            } else {  
                $cat = get_the_category(); $cat = $cat[0];  
                $cats = get_category_parents($cat, TRUE, $delimiter);  
                if ($showCurrent == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);  
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);  
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);  
                echo $cats;  
                if ($showCurrent == 1) echo $before . get_the_title() . $after;  
            }  
  
        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {  
            $post_type = get_post_type_object(get_post_type());  
            echo $before . $post_type->labels->singular_name . $after;  
  
        } elseif ( is_attachment() ) {  
            $parent = get_post($post->post_parent);  
            $cat = get_the_category($parent->ID); $cat = $cat[0];  
            $cats = get_category_parents($cat, TRUE, $delimiter);  
            $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);  
            $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);  
            echo $cats;  
            printf($link, get_permalink($parent), $parent->post_title);  
            if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;  
  
        } elseif ( is_page() && !$post->post_parent ) {  
            if ($showCurrent == 1) echo $before . get_the_title() . $after;  
  
        } elseif ( is_page() && $post->post_parent ) {  
            $parent_id  = $post->post_parent;  
            $breadcrumbs = array();  
            while ($parent_id) {  
                $page = get_page($parent_id);  
                $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));  
                $parent_id  = $page->post_parent;  
            }  
            $breadcrumbs = array_reverse($breadcrumbs);  
            for ($i = 0; $i < count($breadcrumbs); $i++) {  
                echo $breadcrumbs[$i];  
                if ($i != count($breadcrumbs)-1) echo $delimiter;  
            }  
            if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;  
  
        } elseif ( is_tag() ) {  
            echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;  
  
        } elseif ( is_author() ) {  
            global $author;  
            $userdata = get_userdata($author);  
            echo $before . sprintf($text['author'], $userdata->display_name) . $after;  
  
        } elseif ( is_404() ) {  
            echo $before . $text['404'] . $after;  
			echo '</nav>';
			return;
		}
		// Test it:
		elseif ( is_tax() ) {
			$taxonomy_name = get_query_var( 'taxonomy' );
			$term = get_term_by( 'slug', get_query_var( 'term' ), $taxonomy_name );
			$term_output = '';
			while($term->parent != '') {
				$term_output = $delimiter . ' ' . $before . sprintf($link, get_term_link($term), esc_html($term->name))  . $after . $term_output;
				$term = get_term($term->parent, get_query_var('taxonomy') );
			}
			$term_output = $delimiter . ' ' . $before . sprintf($link, get_term_link($term), esc_html($term->name)) . $after . $term_output;

			$taxonomy = get_taxonomy($taxonomy_name);

			if ( $taxonomy ) {
				echo esc_html( $taxonomy->label );

				if ($show_current == 1 && strlen( $term_output ) ) echo ' ' . $term_output;
			}

		}

		
		
		
		
        if ( get_query_var('paged') ) {  
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';  
            echo __('Page','sequoia') . ' ' . get_query_var('paged');  
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
        }
   
        echo '</nav>';  
  
    }  
} // end dimox_breadcrumbs()  
?>