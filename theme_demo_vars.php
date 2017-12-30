<?php
/**
 *	HEADER ORIENTATION
 *
 */
if( isset($_GET['demo_orientation']) ) {
	if( $_GET['demo_orientation'] == 'horizontal') {
		$of_sequoia['orientation'] = 'horizontal';
	}elseif($_GET['demo_orientation'] == 'vertical'){
		$of_sequoia['orientation'] = 'vertical';
	}
}

/**
 *	HEADER TYPES
 *
 */
if( isset($_GET['predefined_headers']) ) {
	$of_sequoia['predefined_headers'] = $_GET['predefined_headers'];
}

if( isset($_GET['min_header_anim']) ) {
	$of_sequoia['min_header_anim'] =$_GET['min_header_anim'];
}

/**
 *	SHOP
 *
 */
// 4 COLUMNS IN CATALOG
if( isset($_GET['catalog_num'])  ) {

	if( $_GET['catalog_num'] == '3_columns' ) {
		$of_sequoia['products_page_settings']['Products columns'] = 3;
	}
}
if( isset($_GET['catalog_num'])  ) {

	if( $_GET['catalog_num'] == '4_columns' ) {
		$of_sequoia['products_page_settings']['Products columns'] = 4;
	}
}
if( isset($_GET['catalog_num'])  ) {

	if( $_GET['catalog_num'] == '5_columns' ) {
		$of_sequoia['products_page_settings']['Products columns'] = 5;
	}
}
if( isset($_GET['catalog_num'])  ) {

	if( $_GET['catalog_num'] == '6_columns' ) {
		$of_sequoia['products_page_settings']['Products columns'] = 6;
	}
}
// FULL WIDTH IN SINGLE PRODUCT PAGE
if( isset($_GET['single_full_width'])  ) {

	if( $_GET['single_full_width'] == true ) {
		$of_sequoia['single_full_width'] = true;
	}
}
// DIFFERENT SINGLE PRODUCT IMAGE - MAGNIFIER
if( isset($_GET['single_product_images'])  ) {

	if( $_GET['single_product_images'] == 'magnifier' ) {
		$of_sequoia['single_product_images'] = 'magnifier';
	}
}
?>