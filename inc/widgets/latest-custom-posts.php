<?php

add_action( 'widgets_init', 'init_latest_custom_posts' );
function init_latest_custom_posts() { return register_widget('latest_custom_posts'); }

class latest_custom_posts extends WP_Widget {
	
	function __construct() {
		parent::__construct( 'latest_custom_posts_widget', $name = 'Latest Custom Posts' );
	}


	function widget( $args, $instance ) {
	
		global $post;
		extract($args);

		// Widget options
		$title 	 = apply_filters('widget_title',  isset($instance['title']) ? $instance['title'] : __('Recent','sequoia') ); // Title		
		$custom_post_list	= isset($instance['types']) ? $instance['types'] : ''; 		
	    $types				= $custom_post_list ? explode(',', $custom_post_list) : ''; 
		$number				= isset($instance['number']) ? $instance['number'] : '';
		$img_width			= isset($instance['img_width']) ? $instance['img_width'] : '';
		
        // Output
		echo $before_widget;
		
	    if ( $title ) echo $before_title . $title . $after_title;
			
		$my_query = new WP_Query(array( 
								'post_type' => $types, 
								'showposts' => $number )
								);
		
		
		if( $my_query->have_posts() ) : 
		?>
		<ul class="widget_latest_custom_posts">
			<?php while($my_query->have_posts()) : $my_query->the_post(); ?>
			<li>
			
				<a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"  class="tip-top" data-tooltip>
				
				<div class="widget-post-thumbs" style="<?php echo ($img_width ? 'width:'.$img_width.'px; height:auto' : '')?>">
					
				
					<?php echo as_image( 'thumbnail' );?>

				</div>
				
				<div class="widget-post-title"><?php echo get_the_title();?></div>
				
				<div class="clearfix"></div>
				
				</a>
				
			</li>
			
			<?php
			wp_reset_query();
			endwhile;
			?>
			
		</ul>
			
		<?php endif; ?>			
		<?php
		// widget close
		echo $after_widget;
	}

	/* Widget control update */
	function update( $new_instance, $old_instance ) {
		$instance    = $old_instance;
		
		//Let's turn that array into something the Wordpress database can store
		$types       = implode(',', (array)$new_instance['types']);

		$instance['title']  = strip_tags( $new_instance['title'] );
		$instance['types']  = $types;
		$instance['number'] = strip_tags( $new_instance['number'] );
		$instance['img_width'] = strip_tags( $new_instance['img_width'] );
		return $instance;
	}
	
	/* widget settings */
	function form( $instance ) {	
	
		    // instance exist? if not set defaults
		    if ( $instance ) {
				$title  = $instance['title'];
		        $types  = $instance['types'];
		        $number = $instance['number'];
		        $img_width = $instance['img_width'];
		    } else {
			    //These are our defaults
				$title  = '';
		        $types  = 'post';
		        $number = '5';
				$img_width = 50;
		    }
			
			//Let's turn $types into an array
			$types = explode(',', $types);
			
			//Count number of post types for select box sizing
			$custom_post_list_types = get_post_types( array( 'public' => true ), 'names' );
			foreach ($custom_post_list_types as $custom_post_list ) {
			   $custom_post_list_ar[] = $custom_post_list;
			}
			$n = count($custom_post_list_ar);
			if($n > 10) { $n = 10;}

			// The widget form
			?>
			
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __( 'Title:','sequoia' ); ?></label>
				<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('types'); ?>"><?php echo __( 'Post type(s):','sequoia' ); ?></label>
				<select name="<?php echo $this->get_field_name('types'); ?>[]" id="<?php echo $this->get_field_id('types'); ?>" class="widefat" style="height: auto;" size="<?php echo $n ?>" multiple>
				<?php 
				$args = array( 'public' => true );
				$post_types = get_post_types( $args, 'names' );
				foreach ($post_types as $post_type ) { 
					if( !( $post_type == 'revision' || $post_type == 'nav_menu_item' || $post_type == 'attachment' || $post_type == 'options' )) {
				?>
					<option value="<?php echo $post_type; ?>" <?php if( in_array($post_type, $types)) { echo 'selected="selected"'; } ?>><?php echo $post_type;?></option>
				<?php 
					} //endif
				}// end foreach
				?>
				</select>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('number'); ?>"><?php echo __( 'Number of items for display:', 'sequoia' ); ?></label>
				<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
			</p>
						
			<p>
				<label for="<?php echo $this->get_field_id('img_width'); ?>"><?php echo __( 'Image width', 'sequoia' ); ?></label>
				<input id="<?php echo $this->get_field_id('img_width'); ?>" name="<?php echo $this->get_field_name('img_width'); ?>" type="text" value="<?php echo $img_width; ?>" size="3" />
			</p>
			
	<?php 
	}

} // class latest_custom_posts

?>