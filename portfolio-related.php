<?php
// PORTFOLIO CUSTOM META:
$image_format			= get_post_meta(get_the_ID(),'as_feat_port_image_format', true);
$related_items_number	= get_post_meta(get_the_ID(),'as_related_portfolios', true) ;

global $post;
$orig_post = $post;

$tags = wp_get_post_tags($post->ID);

if ( !$related_items_number ) {
	$related_items_number = 3;
}

$grid = 'large-' . round (12 / $related_items_number ) .' medium-6 small-12 column';

// GET PORTFOLIO ITEM CATEGORIES TO GET RELATED ITEMS:
$tax_terms	= array();
$terms		= get_the_terms(get_the_ID(), 'portfolio_category');
$i = 1;
foreach($terms as $term ){
	$tax_terms[] =  $term->term_id;
}

$args=array(
	'post_type'			=> 'portfolio',
	//'tag__in' => $tag_ids,
	'post__not_in'		=> array($post->ID),
	'posts_per_page'	=> $related_items_number, // Number of related posts to display.
	'ignore_sticky_posts'=>1,
	'orderby'			=> 'rand',
	'tax_query'			=> array(
							array(
								'taxonomy' => 'portfolio_category',
								'field' => 'id', // can be 'slug' too
								'operator' => 'IN', // NOT IN to exclude
								'terms' => $tax_terms
							)
						)
);

$my_query = new wp_query( $args );

if( $my_query->have_posts() ) {
?>

<div class="clearfix"></div>

<div class="related-portfolio">

	<div class="heading-block">
		<h3 class="block-title"><?php echo __('Related portfolio projects','sequoia'); ?></h3>
	</div>
	
	<?php while( $my_query->have_posts() ) {
	
	$my_query->the_post();
	?>
		<div class="item <?php echo $grid; ?>">
			
			<div class="item-img">
			
				<div class="front">
				
					<?php echo as_image( 'thumbnail' ); ?>
				
				</div>
				
				<div class="back">
							
					<div class="item-overlay"></div>

					<div class="back-buttons">
					
						<a href="<?php echo get_permalink(); ?>" class="button tiny" title="<?php echo esc_attr(strip_tags(get_the_title())); ?>"><div class="icon icon-link" aria-hidden="true" ></div></a>
					
					</div>
				
				</div>
			
			</div>
				
			<h5><a href="<?php the_permalink()?>" title="<?php echo esc_attr(strip_tags(get_the_title())) ?>">	<?php the_title(); ?></a></h5>
				
			<div class="clearfix"></div>
		
		</div>
		
	<?php } //end while ?>

	<div class="clearfix"></div>
	
</div>

<?php 

} //end if
$post = $orig_post;
wp_reset_query();
?>