<?php
/** 
 * Maps block
 * 
 * get map based on Latitude and Longitude
**/
if(!class_exists('AS_Map_Block')) {

class AS_Map_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Google map',
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('as_map_block', $block_options);
	}
	
	function animations_array() {
		
		include ( get_template_directory() .'/inc/functions/animations-icons-arrays.php' );
		return $block_enter_anim_arr;

	}
	
	function form($instance) {
		
		$defaults = array(
			// 51.51379710359708 , -0.09957700967788696 this lat/long is London, St. Paul's cathedral ;) ...
			'latitude'		=> '', 
			'longitude'		=> '', 
			'key'			=> '',
			'title'			=> '',
			'address'		=> '',
			'address2'		=> '',
			'address3'		=> '',
			'address4'		=> '',
			'attach_id'		=> '',
			'width'			=> '100%',
			'height'		=> '420px',
			'map_color'		=> '',
			'map_desatur'	=> '20',
			'zoom'			=> '20',
			'scroll_zoom'	=> true,
			'enter_anim'	=> 'fadeIn',
			'anim_delay'	=> 0,
			'css_classes'	=> '',
		);
		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
				
		?>
				
		<div class="description">
		
			<label for="<?php echo $this->get_field_id('key') ?>">Google API key</label><br/>
				
			<?php echo aq_field_input('key', $block_id, $key,'full' ,'text') ?>
			
			<p class="description clearfix"><strong>API key is required for usage of Google Maps. Visit this address to obtain your API key: 
			<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">https://developers.google.com/maps/documentation/javascript/get-api-key</a></strong></p>
			
		</div>
				
		<div class="description third">
		
			<label for="<?php echo $this->get_field_id('title') ?>">Title or name</label><br/>
				
			<?php echo aq_field_input('title', $block_id, $title,'full' ,'text') ?>
			
		</div>
		
		
		<div class="description third">
		
			<label for="<?php echo $this->get_field_id('enter_anim') ?>">Viewport enter animation</label><br/>	
			<?php
			echo aq_field_select('enter_anim', $block_id, $this->animations_array(), $enter_anim ); 
			?>
			
		</div>
		
		<div class="description third last">
		
			<label for="<?php echo $this->get_field_id('anim_delay') ?>">Animation delay (milisec.)</label><br/>	
			<?php echo aq_field_input('anim_delay', $block_id, $anim_delay, $type="number") ;?>
			
			<p class="description">Type the value for delay of block animation delay. Use the miliseconds ( 1second = 1000 miliseconds; example: 100 for 1/10th of second )</p>
			
		</div>
		
		<hr>
		<h4>ADDRESS FIELDS FOR GOOGLE MAP SEARCH </h4>
		
		<div class="description half">
			
			<label for="<?php echo $this->get_field_id('address2') ?>">Address (street)</label><br/>
				
			<?php echo aq_field_input('address2', $block_id, $address2, 'full' ,'text') ?>
		
		</div>
		<div class="description half last">
		
			<label for="<?php echo $this->get_field_id('address3') ?>">Address ( town, country ) </label><br/>
				
			<?php echo aq_field_input('address3', $block_id, $address3, 'full' ,'text') ?>
		
		</div>
		
		<hr>
		
		<div class="description fourth">
			
			<label for="<?php echo $this->get_field_id('address4') ?>">Address (additional info)</label><br/>
				
			<?php echo aq_field_textarea('address4', $block_id, $address4, $size = 'full') ?>

		</div>
		
		
		<div class="description fourth">
			
			<label for="<?php echo $this->get_field_id('latitude') ?>">
				Location latitude<br/>
				<?php echo aq_field_input('latitude', $block_id, $latitude, 'full', 'text') ?>
			</label>
			
			<label for="<?php echo $this->get_field_id('longitude') ?>">
				Location longitude<br/>
				<?php echo aq_field_input('longitude', $block_id, $longitude, 'full', 'text') ?>
			</label>
			
			<p class="description clearfix">To get your location latitude/longitude values, you can use application on <a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">THIS ADDRESS</a>.<br><br><strong>NOTE:</strong> these values will override google search address fields from above</p>
			
			
		</div>

		
		<div class="description fourth">
			
			<label for="<?php echo $this->get_field_id('width') ?>">Map width</label><br/>
			<?php echo aq_field_input('width', $block_id, $width, 'full', 'text') ?>	
			
			<label for="<?php echo $this->get_field_id('height') ?>">Map height</label><br/>	
			<?php echo aq_field_input('height', $block_id, $height, 'full', 'text') ?>
			
			
			<label for="<?php echo $this->get_field_id('map_color') ?>">
			<?php echo __('Map color','sequoia'); ?>
			</label>
			<?php echo aq_field_color_picker('map_color', $block_id, $map_color, $map_color) ?>

			<div class="slider-controls map-desaturation">
					
				<label for="<?php echo $this->get_field_id('map_desatur') ?>">Desaturation <span><?php echo $map_desatur . '%'; ?></span></label>
				
				<?php echo as_hidden_input('map_desatur', $block_id, $map_desatur, $type = 'hidden')?>
				
				<div class="slider-for-icon"></div>

			</div>
			
			<div class="slider-controls zoom-level">
					
				<label for="<?php echo $this->get_field_id('zoom') ?>">Map zoom level<span> <?php echo $zoom; ?></span></label>
				
				<?php echo as_hidden_input('zoom', $block_id, $zoom, $type = 'hidden')?>
				
				<div class="slider-for-icon"></div>

			</div>
			
			<div class="clearfix"></div>
			
			<label for="<?php echo $this->get_field_id('scroll_zoom') ?>">Disable scroll zoom ?</label><br />
			<?php echo aq_field_checkbox('scroll_zoom', $block_id, $scroll_zoom); ?>
			
		</div>
			
		
		
	
		<div class="description fourth">
			
			<label for="<?php echo $this->get_field_id('attach_id') ?>">Location image (optional)</label>	
			
			<br />
			
			<div class="screenshot member-image">
			
				<input type="hidden" class="placeholder" value="<?php echo PLACEHOLDER_IMAGE; ?>" />
				
				<a href="#" class="remove-media"><img src="<?php echo get_template_directory_uri(); ?>/admin/images/icon-delete.png" /></a>
				
				<?php
				if( $attach_id ) {					
					$imgurl = wp_get_attachment_image_src( $attach_id, 'thumbnail' );
					echo '<img src="'. $imgurl[0] .'" class="att-image" />';
				}else{
					echo '<img src="'. PLACEHOLDER_IMAGE .'" class="att-image" />';
				}
				?>
				
			</div>
			
			<br />
			
			<?php echo as_field_upload('attach_id', $block_id, $attach_id, 'thumbnail'); ?>

		</div>

		<hr>
		
		<div class="description">	
			<label for="<?php echo $this->get_field_id('css_classes') ?>">Additional CSS classes</label>
			<?php echo aq_field_input('css_classes', $block_id, $css_classes, $size = 'full') ?>
		
			<p class="description small">Add custom css classes, defined in child theme css file, or custom css (in Theme options). Multiple classes can be added, separated by space.</p>
		</div>	
		

		<?php
		
	}
	
	function block($instance) {
		
		$key = '';
		
		extract($instance);
		
		if( $key !== '' ) {
		//$key = "AIzaSyCKZ6qL_VsvJahq0yZY7OqS3vDqhqh3usU";
		
		wp_register_script('gmap', 'http://maps.googleapis.com/maps/api/js?v=3&key='. $key );
		wp_enqueue_script ('gmap', 'http://maps.googleapis.com/maps/api/js?v=3&key='. $key ,'', '1.0');
		
		if ( $enter_anim != 'none' && !wp_style_is( 'animate', 'enqueued' )) {
			
			wp_register_style( 'animate', get_template_directory_uri() . '/css/animate.css');
			wp_enqueue_style( 'animate' );
			
		}
		?>
		
		<div id="map-<?php echo $block_id; ?>-holder" class="content-block inner-wrapper<?php echo ($enter_anim != 'none') ? ' to-anim' :''; echo $css_classes ? ' '. $css_classes : ''; ?>" >

            <?php
			$add_str  = '<div class="marker">';
			$add_str .=  $title   ? '<p><strong>' . $title.'</strong></p>' : '';
			$add_str .=  $address2  ? '<p>' . $address2.'</p>' : '';
			$add_str .=  $address3  ? '<p>' . $address3.'</p>' : '';
			$add_str .=  $address4  ? '<p>' . $address4.'</p>' : '';
			$add_str .=  $attach_id ? '<div class="entry-image">'. wp_get_attachment_image( $attach_id, 'thumbnail' ).'</div>' : '';
			$add_str  .= '</div>';

			$add_str = wpautop($add_str);
			$address_final = json_encode($add_str);
			
			
			// GET LONGITUDE AND LATITUDE BY USING ADDRESS:
			$address_flds = $address2 .', '. $address3; 
			$prepAddr = str_replace(' ','+',$address_flds);
			// IF THERE IS ADDRESS DATA AND NO "MANUAL" LONGITUDE/LATITUDE INPUT
			if( $prepAddr && !$latitude && !$longitude ) {
				
				$geocode = wp_remote_get('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr );
				 
				$output= json_decode($geocode);
				
				//	IF THERE'S AND ERROR IN ADDRESS, AND GOGLE CAN'T FIND IT
				if( empty( $output->results ) || ($output->status != 'OK') ) {
					echo '<h3 style="text-align:center">' . __("Google maps error","sequoia") .' :</h3>';
					echo '<p style="text-align:center">' . __("Please check your address inputs - there's a probable error in data, or use manual longitude and latitude inputs.","sequoia") .'</p>';
					return;
				} 

				$lat = $output->results[0]->geometry->location->lat;
				$long = $output->results[0]->geometry->location->lng;	
			}
			
			// IF LATITUDE AND LONGITUDE ARE ENTERED MANUALLY:
			if( $latitude && $longitude ) {
				$lat	= $latitude;
				$long	= $longitude;
			}
	
			?>
			
			<script type="text/javascript">
            function initialize_<?php echo esc_js( $block_id ); ?>() {

                var leeds = new google.maps.LatLng( <?php echo $lat; ?>, <?php echo $long; ?> );

                var firstLatlng = new google.maps.LatLng( <?php echo $lat; ?>, <?php echo $long; ?> );              

                var firstOptions = {
                    scrollwheel: <?php echo $scroll_zoom ? 'false' : 'true'; ?>,
					zoom: <?php echo $zoom ? $zoom : '16'; ?>,
                    center: firstLatlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP 
                };

                var map = new google.maps.Map(document.getElementById("map-<?php echo $block_id; ?>"), firstOptions);

                firstmarker = new google.maps.Marker({
                    map:map,
                    draggable:false,
                    animation: google.maps.Animation.DROP,
                    title: "<?php echo $title ? $title : ''; ?>",
                    position: leeds
                });

                var contentString1 = <?php echo $address_final; ?>;


                var infowindow1 = new google.maps.InfoWindow({
                    content: contentString1
                });

                google.maps.event.addListener(firstmarker, 'click', function() {
                    infowindow1.open(map,firstmarker);
                });
				
				var styles = [
					{
						featureType: "all",
						stylers: [
							{ hue: "<?php echo $map_color; ?>"},
							{ saturation: -<?php echo $map_desatur; ?> }
						]
					},{
						featureType: "road.arterial",
						elementType: "geometry",
						stylers: [
							{ hue: "#00FFEE" },
							{ saturation: 50 }
						]
					},{
						featureType: "poi.business",
						elementType: "labels",
						stylers: [
							{ visibility: "on" }
						]
					}
				]

				map.setOptions({styles: styles});

            }
            </script>

            <div class="google-map">

                <div id="map-<?php echo $block_id; ?>" style="width: <?php echo $width; ?>; height:<?php echo $height;?>"></div>  

            </div>

        </div>  
		
		<?php $delay = $anim_delay ? $anim_delay : 100 ?>
			
		<script>
		jQuery(document).ready( function($) {
			
			var thisBlock = $('#map-<?php echo $block_id; ?>-holder');
			
			if ( !window.isMobile && !window.isIE9 ) {

				thisBlock.waypoint(
				
					function(direction) {
						
						if( direction === "up" ) {	
							
							thisBlock.removeClass('animated <?php echo $enter_anim;?>').addClass('to-anim');
							
						}else if( direction === "down" ) {
							
							setTimeout(function(){
							   thisBlock.addClass('animated <?php echo $enter_anim;?>').removeClass('to-anim');
							}, <?php echo $delay; ?>);
						}
					}, 
					{ offset: "98%" }	
				
				);

			}else{
		
				thisBlock.each( function() {
					
					$(this).removeClass('to-anim');
				
				});
				
			}
		
		});
		jQuery(window).load( function($){
			initialize_<?php echo esc_js( $block_id ); ?>(); 
		});
		</script>
		
		<?php
		// IF NO API KEY :
		}else{
			?>
			<div style="text-align: center; padding: 30px; margin: 30px; border: 1px solid #999;">
				<h5><?php echo esc_html__('Google Maps Alert','sequoia') ?></h5>
				<br>
				<?php 
				$gmap_api_url = "https://developers.google.com/maps/documentation/javascript/get-api-key";
				echo esc_html__("Missing API key for Google Maps.",'sequoia');
				echo '<a href="'. esc_url( $gmap_api_url ) .'" target="_blank">' . esc_html__("Get your API key here ",'sequoia') .'</a>'; ?>
			</div>
			
			<?php
		}
		
	} // function block
	
} // end class

} // end classs_exists