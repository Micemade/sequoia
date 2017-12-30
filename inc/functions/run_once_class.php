<?php
if (!class_exists('run_once')){
    class run_once{
        function run($key){
            $run_once_option = get_option('run_once');
            if (isset($run_once_option[$key]) && $run_once_option[$key]){
                return false;
            }else{
                $run_once_option[$key] = true;
                update_option('run_once',$run_once_option);
                return true;
            }
        }
        function clear($key){
            $run_once_option = get_option('run_once');
            if (isset($run_once_option[$key])){
                unset($run_once_option[$key]);
            }
			update_option('run_once',$run_once_option);
        }
    }
}
/*============== INITIAL IMAGE SIZES SETUP ===================*/
$run_once = new run_once;
if ( $run_once->run('init_image_values_added') ){
	init_as_img_setup();
}
//
function init_as_img_setup() {
	// DEFAULT SIZES DOUBLED for HIGH RES
	update_option( 'large_size_w', 1280 );
	update_option( 'large_size_h', 960 );
	update_option( 'medium_size_w', 600 );
	update_option( 'medium_size_h', 600 );
	update_option( 'thumbnail_size_w', 300 );
	update_option( 'thumbnail_size_h', 300 );
}
?>