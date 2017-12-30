<?php 
/*
*	SITEMAP FOR SITE_WIDE USAGE
*	- search
*	- 404 page
*/
?>
<h2><?php echo __('Site map','sequoia'); ?></h2>

<div class="row sitemap">

	<?php // POSTS IN CATEGORIES ?>
	
	<div class="large-4 medium-4 small-12 column">
	
		<h3 id="posts"><?php echo __('Posts','sequoia'); ?></h3>
		
		<ul>
			<?php
			// Add categories you'd like to exclude in the exclude here
			$cats = get_categories('exclude=');
			foreach ($cats as $cat) {
				echo "<li><h4>".$cat->cat_name."</h4>";
				echo "<ul>";
				query_posts('posts_per_page=-1&cat='.$cat->cat_ID);
				while(have_posts()) {
					the_post();
					$category = get_the_category();
					// Only display a post link once, even if it's in multiple categories
					if ($category[0]->cat_ID == $cat->cat_ID) {
						echo '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
					}
				}
				echo "</ul>";
				echo "</li>";
			}
			?>
		</ul>
		
	</div>	
	
	<div class="large-4 medium-4 small-12 column">
	<?php // LIST OF PAGES ;?>
		<h3 id="pages"><?php echo __('Pages','sequoia'); ?></h3>
		<ul>
			<?php
			// Add pages you'd like to exclude in the exclude here
			wp_list_pages(
			  array(
				'exclude' => '',
				'title_li' => '',
			  )
			);
			?>
		</ul>
		
	</div>	
	
	<div class="large-4 medium-4 small-12 column">	
	
		<?php // LIST OF AUTHORS ?>
		
		<h3 id="authors"><?php echo __('Authors','sequoia'); ?></h3>
		<ul>
			<?php
			wp_list_authors(
			array(
			'exclude_admin' => false,
			)
			);
			?>
		</ul>
		
	</div>	
	
</div>
	
<div class="clearfix"></div>
	
<div class="row sitemap">

	<?php // DIFFERENT TAXONOMIES BY POST TYPES:
	
	//echo '<pre>'; print_r(get_post_types( array('public' => true) )); echo '</pre>';
	foreach( get_post_types( array('public' => true) ) as $post_type ) {
		if ( in_array( $post_type, array('post','page','attachment','options','product_variation','shop_coupon') ) )
			continue;
			
		$pt = get_post_type_object( $post_type );
		
		echo '<div class="large-4 medium-4 small-12 column">';
		echo '<h3>'.$pt->labels->name.'</h3>';
		echo '<ul>';

		query_posts('post_type='.$post_type.'&posts_per_page=-1');
		while( have_posts() ) {
			the_post();
			echo '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
		}

		echo '</ul>';
		echo '</div>';
	}
	?>
	
</div>

<div class="clearfix"></div>