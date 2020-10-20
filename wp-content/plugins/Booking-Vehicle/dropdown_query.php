<?php

$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

	$category_name = $_REQUEST['category'];

	global $wpdb;
 
	echo '<option>Select vehicle</option>';

	$args = array('post_type'=>'post','category_name' => $category_name);
	$the_query = get_posts( $args );
	foreach ($the_query as $post) {
		echo '<option value="'.$post->post_title.'" id="'.get_post_meta($post->ID, 'price', true).'">"'.$post->post_title.'"</option>';
	}
?> 
 